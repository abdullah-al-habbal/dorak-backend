<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\BarberAffiliation\Http\Resources\BarberAffiliationResource;
use Modules\BarberAffiliation\Models\BarberAffiliationModel;
use Modules\Core\Http\Actions\BaseApiAction;

final class RejectAffiliationAction extends BaseApiAction
{
    public function __invoke(string $affiliation): JsonResponse
    {
        $affiliation = BarberAffiliationModel::findOrFail($affiliation);

        if ($affiliation->status !== 'pending') {
            return $this->businessError(message: 'Affiliation is not in pending status');
        }

        $affiliation->update([
            'status' => 'rejected',
        ]);

        return $this->ok(
            data: new BarberAffiliationResource($affiliation),
            message: 'Affiliation rejected successfully',
        );
    }
}
