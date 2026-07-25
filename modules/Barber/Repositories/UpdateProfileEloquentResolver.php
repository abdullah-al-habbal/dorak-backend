<?php

declare(strict_types=1);

namespace Modules\Barber\Repositories;

use Modules\Barber\CQRS\Command\Barber\UpdateProfileCommand;
use Modules\Barber\Models\BarberModel;

final class UpdateProfileEloquentResolver
{
    public function resolve(UpdateProfileCommand $command): BarberModel
    {
        $barber = BarberModel::findOrFail($command->barberId);

        $data = array_filter([
            'name' => $command->name,
            'email' => $command->email,
            'password' => $command->password,
            'is_freelancer' => $command->isFreelancer,
        ], fn ($value) => $value !== null);

        $barber->update($data);

        return $barber->fresh();
    }
}
