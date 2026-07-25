<?php

declare(strict_types=1);

namespace Modules\Barber\Repositories;

use Modules\Barber\CQRS\Command\Barber\UpdateTravelRadiusCommand;
use Modules\Barber\Models\BarberModel;

final class UpdateTravelRadiusEloquentResolver
{
    public function resolve(UpdateTravelRadiusCommand $command): BarberModel
    {
        $barber = BarberModel::findOrFail($command->barberId);

        $barber->update([
            'travel_radius' => $command->travelRadius,
            'latitude' => $command->latitude,
            'longitude' => $command->longitude,
        ]);

        return $barber->fresh();
    }
}
