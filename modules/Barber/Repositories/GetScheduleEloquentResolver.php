<?php

declare(strict_types=1);

namespace Modules\Barber\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Barber\CQRS\Query\Barber\GetScheduleQuery;
use Modules\Barber\Models\BarberScheduleModel;

final class GetScheduleEloquentResolver
{
    public function resolve(GetScheduleQuery $query): Collection
    {
        return BarberScheduleModel::where('barber_id', $query->barberId)
            ->orderBy('day_of_week')
            ->get();
    }
}
