<?php

declare(strict_types=1);

namespace Modules\Branch\Http\Actions\Branch;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\BarberAffiliation\Enums\AffiliationStatus;
use Modules\BarberAffiliation\Models\BarberAffiliationModel;
use Modules\Core\Enums\ErrorCodeEnum;
use Modules\Core\Enums\SuccessCodeEnum;
use Modules\Core\Http\Actions\BaseApiAction;

final class AcceptAffiliationAction extends BaseApiAction
{
    public function __invoke(Request $request, string $affiliation): JsonResponse
    {
        $branch = $request->user('branch_api');

        $affiliationModel = BarberAffiliationModel::where('affiliable_id', $branch->id)
            ->where('affiliable_type', 'branch')
            ->findOrFail($affiliation);

        if ($affiliationModel->status !== AffiliationStatus::Pending) {
            return $this->businessError(ErrorCodeEnum::BAD_REQUEST, message: 'Affiliation is not in pending status');
        }

        $affiliationModel->update([
            'status' => AffiliationStatus::Accepted,
            'accepted_at' => now(),
        ]);

        return $this->success(
            data: $affiliationModel->fresh()->load('barber'),
            code: SuccessCodeEnum::UPDATED,
        );
    }
}
