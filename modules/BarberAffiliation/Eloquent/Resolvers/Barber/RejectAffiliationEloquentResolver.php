<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\Eloquent\Resolvers\Barber;

use Modules\BarberAffiliation\CQRS\Command\Barber\RejectAffiliationCommand;
use Modules\BarberAffiliation\Enums\AffiliationStatus;
use Modules\BarberAffiliation\Models\BarberAffiliationModel;
use Modules\BarberAffiliation\ValuesObjects\RejectAffiliationResult;

final class RejectAffiliationEloquentResolver
{
    public function resolve(RejectAffiliationCommand $command): RejectAffiliationResult
    {
        $affiliation = BarberAffiliationModel::findOrFail($command->affiliationId);

        if ($affiliation->status !== AffiliationStatus::Pending) {
            return RejectAffiliationResult::invalidStatus();
        }

        $affiliation->update([
            'status' => AffiliationStatus::Rejected,
        ]);

        return RejectAffiliationResult::success($affiliation);
    }
}
