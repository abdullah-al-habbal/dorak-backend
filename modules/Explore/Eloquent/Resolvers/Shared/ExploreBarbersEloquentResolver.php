<?php

declare(strict_types=1);

namespace Modules\Explore\Eloquent\Resolvers\Shared;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Modules\Barber\Models\BarberModel;
use Modules\ClientRecommendation\Models\ClientPreferenceVectorModel;
use Modules\ClientRecommendation\Models\EntityEmbeddingModel;
use Modules\ClientRecommendation\Models\RecommendationEdgeModel;
use Modules\ClientRecommendation\ValuesObjects\RecommendationFactorWeightsValueObject;
use Modules\Explore\CQRS\Query\Shared\ExploreBarbersQuery;

final class ExploreBarbersEloquentResolver
{
    public function resolve(ExploreBarbersQuery $payload): LengthAwarePaginator
    {
        $haversine = sprintf(
            '(6371 * acos(cos(radians(%f)) * cos(radians(latitude)) * cos(radians(longitude) - radians(%f)) + sin(radians(%f)) * sin(radians(latitude))))',
            $payload->lat, $payload->lng, $payload->lat
        );

        $subQuery = BarberModel::query()
            ->select('barbers.*')
            ->selectRaw("{$haversine} AS distance")
            ->where('is_freelancer', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        $subQuery = $this->applyFilters($subQuery, $payload);

        $query = BarberModel::query()->fromSub($subQuery, 'barbers_sub')
            ->where('distance', '<', $payload->radius)
            ->orderBy('distance');

        $paginator = $query->paginate($payload->perPage);

        if ($payload->clientId === null) {
            return $paginator;
        }

        $barberIds = $paginator->pluck('id')->toArray();

        $edgeWeights = RecommendationEdgeModel::where('source_type', 'client')
            ->where('source_id', $payload->clientId)
            ->where('target_type', 'barber')
            ->whereIn('target_id', $barberIds)
            ->pluck('weight', 'target_id');

        $clientEmbedding = ClientPreferenceVectorModel::where('client_id', $payload->clientId)->value('embedding');

        $targetEmbeddings = [];
        if ($clientEmbedding !== null) {
            $targetEmbeddings = EntityEmbeddingModel::where('entity_type', 'barber')
                ->whereIn('entity_id', $barberIds)
                ->pluck('embedding', 'entity_id');
        }

        $faceMatchIds = [];
        if ($payload->faceShapeCompatible !== null) {
            $faceMatchIds = BarberModel::query()
                ->whereIn('id', $barberIds)
                ->whereHas('services', fn ($q) =>
                    $q->whereHas('catalogItem', fn ($q) =>
                        $q->whereJsonContains('face_shapes', $payload->faceShapeCompatible)
                    )
                )
                ->pluck('id')
                ->toArray();
        }

        $weights = RecommendationFactorWeightsValueObject::defaults();

        $scored = $paginator->getCollection()->map(function ($barber) use ($edgeWeights, $faceMatchIds, $payload, $clientEmbedding, $targetEmbeddings, $weights) {
            $geographic = 1 / (1 + $barber->distance / $payload->radius);
            $edgeBoost = (float) $edgeWeights->get($barber->id, 0);

            $vectorSimilarity = 0.0;
            if ($clientEmbedding !== null && isset($targetEmbeddings[$barber->id])) {
                $vectorSimilarity = $this->cosineSimilarity($clientEmbedding, $targetEmbeddings[$barber->id]);
            }

            $faceMatch = in_array($barber->id, $faceMatchIds) ? 1 : 0;

            $barber->compatibility_score = $geographic * $weights->geographic()
                + $vectorSimilarity * $weights->alpha()
                + $edgeBoost * $weights->beta()
                + $faceMatch * $weights->gamma();

            return $barber;
        });

        $scored = $scored->sortByDesc('compatibility_score')->values();
        $scored->each(fn ($barber, $i) => $barber->rank = $i + 1);
        $paginator->setCollection($scored);

        return $paginator;
    }

    private function applyFilters($query, ExploreBarbersQuery $payload)
    {
        if ($payload->catalogItemIds !== null) {
            $query->whereHas('services', fn ($q) =>
                $q->whereIn('catalog_item_id', $payload->catalogItemIds)
            );
        }

        if ($payload->availableNow !== null && $payload->availableNow) {
            $query->whereDoesntHave('bookings', fn ($b) =>
                $b->where('status', 'confirmed')
                    ->where('time_slot', '<=', DB::raw('NOW()'))
                    ->where(DB::raw("time_slot + INTERVAL '1 hour'"), '>', DB::raw('NOW()'))
            );
        }

        if ($payload->priceRangeMin !== null || $payload->priceRangeMax !== null) {
            $query->whereHas('services', fn ($q) => $q
                ->when($payload->priceRangeMin !== null, fn ($q) => $q->where('price', '>=', $payload->priceRangeMin))
                ->when($payload->priceRangeMax !== null, fn ($q) => $q->where('price', '<=', $payload->priceRangeMax))
            );
        }

        if ($payload->ratingMin !== null) {
            $query->whereHas('reviews', fn ($q) =>
                $q->select(DB::raw('avg(rating)'))
                    ->groupBy('subject_id')
                    ->having(DB::raw('avg(rating)'), '>=', $payload->ratingMin)
            );
        }

        return $query;
    }
    private function cosineSimilarity(array $a, array $b): float
    {
        $dot = 0.0;
        $normA = 0.0;
        $normB = 0.0;
        foreach ($a as $i => $val) {
            $dot += $val * ($b[$i] ?? 0.0);
            $normA += $val * $val;
        }
        foreach ($b as $val) {
            $normB += $val * $val;
        }
        $denom = sqrt($normA) * sqrt($normB);
        return $denom > 0.0 ? $dot / $denom : 0.0;
    }
}
