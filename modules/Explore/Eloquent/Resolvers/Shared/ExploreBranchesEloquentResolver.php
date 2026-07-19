<?php

declare(strict_types=1);

namespace Modules\Explore\Eloquent\Resolvers\Shared;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Modules\Branch\Models\BranchModel;
use Modules\ClientRecommendation\Models\RecommendationEdgeModel;
use Modules\Explore\CQRS\Query\Shared\ExploreBranchesQuery;

final class ExploreBranchesEloquentResolver
{
    public function resolve(ExploreBranchesQuery $payload): LengthAwarePaginator
    {
        $haversine = sprintf(
            '(6371 * acos(cos(radians(%f)) * cos(radians(latitude)) * cos(radians(longitude) - radians(%f)) + sin(radians(%f)) * sin(radians(latitude))))',
            $payload->lat, $payload->lng, $payload->lat
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

        $faceMatchIds = [];
        if ($payload->faceShapeCompatible !== null) {
            $faceMatchIds = BranchModel::query()
                ->whereIn('id', $branchIds)
                ->whereHas('offeredServices', fn ($q) =>
                    $q->whereHas('catalogItem', fn ($q) =>
                        $q->whereJsonContains('face_shapes', $payload->faceShapeCompatible)
                    )
                )
                ->pluck('id')
                ->toArray();
        }

        $scored = $paginator->getCollection()->map(function ($branch) use ($edgeWeights, $faceMatchIds, $payload) {
            $geographic = 1 / (1 + $branch->distance / $payload->radius);
            $edgeBoost = (float) $edgeWeights->get($branch->id, 0);
            $faceMatch = in_array($branch->id, $faceMatchIds) ? 1 : 0;

            $branch->compatibility_score = $geographic * 0.35 + $edgeBoost * 0.55 + $faceMatch * 0.1;

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
            $query->whereHas('offeredServices', fn ($q) =>
                $q->whereIn('catalog_item_id', $payload->catalogItemIds)
            );
        }

        if ($payload->availableNow !== null && $payload->availableNow) {
            $query->whereHas('chairs', fn ($q) =>
                $q->whereDoesntHave('bookings', fn ($b) =>
                    $b->where('status', 'confirmed')
                        ->where('time_slot', '<=', DB::raw('NOW()'))
                        ->where(DB::raw("time_slot + INTERVAL '1 hour'"), '>', DB::raw('NOW()'))
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
            $query->whereHas('reviews', fn ($q) =>
                $q->select(DB::raw('avg(rating)'))
                    ->groupBy('subject_id')
                    ->having(DB::raw('avg(rating)'), '>=', $payload->ratingMin)
            );
        }

        return $query;
    }
}
