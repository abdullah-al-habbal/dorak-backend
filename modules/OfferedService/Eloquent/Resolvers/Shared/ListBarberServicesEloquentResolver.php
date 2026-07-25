<?php

declare(strict_types=1);

namespace Modules\OfferedService\Eloquent\Resolvers\Shared;

use Illuminate\Database\Eloquent\Collection;
use Modules\Barber\Models\BarberModel;
use Modules\OfferedService\CQRS\Query\Shared\ListBarberServicesQuery;

final class ListBarberServicesEloquentResolver
{
    public function resolve(ListBarberServicesQuery $query): Collection
    {
        $barber = BarberModel::with('services.currency')->findOrFail($query->barberId);

        return $barber->services;
    }
}
