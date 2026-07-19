<?php

declare(strict_types=1);

namespace Modules\Explore\Eloquent\Resolvers\Shared;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Modules\Barber\Models\BarberModel;
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

        return $query->paginate($payload->perPage);
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
}
