<?php

declare(strict_types=1);

namespace Modules\Activation\Eloquent\Resolvers\Business;

use Modules\Activation\CQRS\Command\Business\ToggleActivationCommand;
use Modules\Activation\Enums\ActivationStatusEnum;
use Modules\Activation\Models\ActivationLogModel;
use Modules\Barber\Models\BarberModel;

final class ToggleActivationEloquentResolver
{
    public function resolve(ToggleActivationCommand $command): ActivationLogModel
    {
        $barber = BarberModel::findOrFail($command->barberId);

        return ActivationLogModel::create([
            'activable_id' => $barber->id,
            'activable_type' => $barber->getMorphClass(),
            'status' => $command->activate ? ActivationStatusEnum::Enabled : ActivationStatusEnum::Disabled,
            'reason' => $command->reason,
            'activated_at' => now(),
        ]);
    }
}
