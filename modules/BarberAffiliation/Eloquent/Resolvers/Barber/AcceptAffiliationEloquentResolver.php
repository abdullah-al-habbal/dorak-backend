<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\Eloquent\Resolvers\Barber;

use Modules\BarberAffiliation\CQRS\Command\Barber\AcceptAffiliationCommand;
use Modules\BarberAffiliation\Enums\AffiliationStatus;
use Modules\BarberAffiliation\Models\BarberAffiliationModel;
use Modules\BarberAffiliation\ValuesObjects\AcceptAffiliationResult;

final class AcceptAffiliationEloquentResolver
{
    public function resolve(AcceptAffiliationCommand $command): AcceptAffiliationResult
    {
        $affiliation = BarberAffiliationModel::findOrFail($command->affiliationId);

        if ($affiliation->status !== AffiliationStatus::Pending) {
            return AcceptAffiliationResult::invalidStatus();
        }

        $affiliation->update([
            'status' => AffiliationStatus::Accepted,
            'accepted_at' => now(),
        ]);

        return AcceptAffiliationResult::success($affiliation);
    }
}
