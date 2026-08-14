<?php

declare(strict_types=1);

namespace Modules\Explore\Eloquent\Resolvers\Shared;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Modules\Branch\Models\BranchModel;
use Modules\ClientRecommendation\Models\ClientPreferenceVectorModel;
use Modules\ClientRecommendation\Models\EntityEmbeddingModel;
use Modules\ClientRecommendation\Models\RecommendationEdgeModel;
use Modules\ClientRecommendation\Services\ExploreRankingWeightsResolver;
use Modules\Explore\CQRS\Query\Shared\ExploreBranchesQuery;

final class ExploreBranchesEloquentResolver
{
    public function __construct(
        private readonly ExploreRankingWeightsResolver $weightsResolver,
    ) {}

    public function resolve(ExploreBranchesQuery $payload): LengthAwarePaginator
    {
        $haversine = sprintf(
            '(6371 * acos(cos(radians(%f)) * cos(radians(latitude)) * cos(radians(longitude) - radians(%f)) + sin(radians(%f)) * sin(radians(latitude))))',
            $payload->latitude, $payload->longitude, $payload->latitude
        );

        $subQuery = BranchModel::query()
            ->select('branches.*')
            ->selectRaw("{$haversine} AS distance")
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        $subQuery->whereHas('brand', fn ($q) => $q->where('universe', $payload->universe));

        $subQuery = $this->applyFilters($subQuery, $payload);

        $query = BranchModel::query()->fromSub($subQuery, 'branches_sub')
            ->where('distance', '<', $payload->radius)
            ->orderBy('distance');

        $paginator = $query->paginate($payload->perPage);

        if ($payload->clientId === null) {
            return $paginator;
        }

        $branchIds = $paginator->pluck('id')->toArray();

        $edgeWeights = RecommendationEdgeModel::where('source_type', 'client')
            ->where('source_id', $payload->clientId)
            ->where('target_type', 'branch')
            ->whereIn('target_id', $branchIds)
            ->pluck('weight', 'target_id');

        $clientEmbedding = ClientPreferenceVectorModel::where('client_id', $payload->clientId)->value('embedding');

        $targetEmbeddings = [];
        if ($clientEmbedding !== null) {
            $targetEmbeddings = EntityEmbeddingModel::where('entity_type', 'branch')
                ->whereIn('entity_id', $branchIds)
                ->pluck('embedding', 'entity_id');
        }

        $faceMatchIds = [];
        if ($payload->faceShapeCompatible !== null) {
            $faceMatchIds = BranchModel::query()
                ->whereIn('id', $branchIds)
                ->whereHas('offeredServices', fn ($q) => $q->whereHas('catalogItem', fn ($q) => $q->whereJsonContains('face_shapes', $payload->faceShapeCompatible)
                )
                )
                ->pluck('id')
                ->toArray();
        }

        $weights = $this->weightsResolver->resolveFor($payload->clientId);

        $scored = $paginator->getCollection()->map(function ($branch) use ($edgeWeights, $faceMatchIds, $payload, $clientEmbedding, $targetEmbeddings, $weights) {
            $geographic = 1 / (1 + $branch->distance / $payload->radius);
            $edgeBoost = (float) $edgeWeights->get($branch->id, 0);

            $vectorSimilarity = 0.0;
            if ($clientEmbedding !== null && isset($targetEmbeddings[$branch->id])) {
                $vectorSimilarity = $this->cosineSimilarity($clientEmbedding, $targetEmbeddings[$branch->id]);
            }

            $faceMatch = in_array($branch->id, $faceMatchIds) ? 1 : 0;

            $branch->compatibility_score = $geographic * $weights->geographic()
                + $vectorSimilarity * $weights->alpha()
                + $edgeBoost * $weights->beta()
                + $faceMatch * $weights->gamma();

            return $branch;
        });

        $scored = $scored->sortByDesc('compatibility_score')->values();

        $scored->each(fn ($branch, $i) => $branch->rank = $i + 1);

        $paginator->setCollection($scored);

        return $paginator;
    }

    private function applyFilters($query, ExploreBranchesQuery $payload)
    {
        if ($payload->catalogItemIds !== null) {
            $query->whereHas('offeredServices', fn ($q) => $q->whereIn('catalog_item_id', $payload->catalogItemIds)
            );
        }

        if ($payload->availableNow !== null && $payload->availableNow) {
            $query->whereHas('chairs', fn ($q) => $q->whereDoesntHave('bookings', fn ($b) => $b->where('status', 'confirmed')
                ->where('time_slot', '<=', now())
                ->where('time_slot', '>', now()->subHour())
            )
            );
        }

        if ($payload->priceRangeMin !== null || $payload->priceRangeMax !== null) {
            $query->whereHas('offeredServices', fn ($q) => $q
                ->when($payload->priceRangeMin !== null, fn ($q) => $q->where('price', '>=', $payload->priceRangeMin))
                ->when($payload->priceRangeMax !== null, fn ($q) => $q->where('price', '<=', $payload->priceRangeMax))
            );
        }

        if ($payload->ratingMin !== null) {
            $query->whereHas('reviews', fn ($q) => $q->select(DB::raw('avg(rating)'))
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
