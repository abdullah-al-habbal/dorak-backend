<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\Eloquent\Resolvers\Barber;

use Modules\Barber\Models\BarberModel;
use Modules\BarberAffiliation\CQRS\Command\Barber\CreateAffiliationCommand;
use Modules\BarberAffiliation\Enums\AffiliationStatus;
use Modules\BarberAffiliation\Models\BarberAffiliationModel;

final class CreateAffiliationEloquentResolver
{
    public function resolve(CreateAffiliationCommand $command): BarberAffiliationModel
    {
        $barber = BarberModel::findOrFail($command->barberId);

        $existing = BarberAffiliationModel::where('barber_id', $barber->id)
            ->where('status', AffiliationStatus::Accepted)
            ->exists();

        if ($existing) {
            throw new BarberAlreadyAffiliatedException;
        }

        return BarberAffiliationModel::create([
            'barber_id' => $barber->id,
            'affiliable_id' => $command->affiliableId,
            'affiliable_type' => $command->affiliableType,
            'commission_rate' => $command->commissionRate,
            'status' => AffiliationStatus::Pending,
            'invited_at' => now(),
        ]);
    }
}
