<?php

declare(strict_types=1);

namespace Modules\Explore\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Barber\Models\BarberModel;

final class ExploreBarbersEloquentResolver
{
    public function search(float $lat, float $lng, float $radius, int $perPage): LengthAwarePaginator
    {
        $haversine = sprintf(
            '(6371 * acos(cos(radians(%f)) * cos(radians(latitude)) * cos(radians(longitude) - radians(%f)) + sin(radians(%f)) * sin(radians(latitude))))',
            $lat, $lng, $lat
        );

        $subQuery = BarberModel::query()
            ->select('barbers.*')
            ->selectRaw("{$haversine} AS distance")
            ->where('is_freelancer', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        $query = BarberModel::query()->fromSub($subQuery, 'barbers_sub')
            ->where('distance', '<', $radius)
            ->orderBy('distance');

        return $query->paginate(min($perPage, 100));
    }
}
