<?php

declare(strict_types=1);

namespace Modules\Explore\Eloquent\Resolvers;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Barber\Models\BarberModel;
use Modules\Explore\CQRS\Query\ExploreBarbersQuery;

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

        $query = BarberModel::query()->fromSub($subQuery, 'barbers_sub')
            ->where('distance', '<', $payload->radius)
            ->orderBy('distance');

        return $query->paginate($payload->perPage);
    }
}
