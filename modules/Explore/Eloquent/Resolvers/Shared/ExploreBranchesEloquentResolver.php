<?php

declare(strict_types=1);

namespace Modules\Explore\Eloquent\Resolvers\Shared;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Modules\Branch\Models\BranchModel;
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

        return $query->paginate($payload->perPage);
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
