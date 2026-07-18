<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\Http\Actions\Barber;

use Illuminate\Http\JsonResponse;
use Modules\BarberAffiliation\Enums\AffiliationStatus;
use Modules\BarberAffiliation\Http\Resources\Barber\BarberAffiliationResource;
use Modules\BarberAffiliation\Models\BarberAffiliationModel;
use Modules\Core\Enums\ErrorCodeEnum;
use Modules\Core\Http\Actions\BaseApiAction;

final class RejectAffiliationAction extends BaseApiAction
{
    public function __invoke(string $affiliation): JsonResponse
    {
        $affiliation = BarberAffiliationModel::findOrFail($affiliation);

        if ($affiliation->status !== AffiliationStatus::Pending) {
            return $this->businessError(ErrorCodeEnum::BAD_REQUEST, message: 'Affiliation is not in pending status');
        }

        $affiliation->update([
            'status' => AffiliationStatus::Rejected,
        ]);

        return $this->ok(
            data: new BarberAffiliationResource($affiliation),
            message: 'Affiliation rejected successfully',
        );
    }
}
