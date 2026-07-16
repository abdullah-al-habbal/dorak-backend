<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\Barber\Models\BarberModel;
use Modules\BarberAffiliation\Enums\AffiliationStatus;
use Modules\BarberAffiliation\Http\Requests\CreateAffiliationRequest;
use Modules\BarberAffiliation\Http\Resources\BarberAffiliationResource;
use Modules\BarberAffiliation\Models\BarberAffiliationModel;
use Modules\Core\Enums\ErrorCodeEnum;
use Modules\Core\Http\Actions\BaseApiAction;

final class CreateAffiliationAction extends BaseApiAction
{
    public function __invoke(CreateAffiliationRequest $request, string $barber): JsonResponse
    {
        $barber = BarberModel::findOrFail($barber);

        $existing = BarberAffiliationModel::where('barber_id', $barber->id)
            ->where('status', AffiliationStatus::Accepted)
            ->exists();

        if ($existing) {
            return $this->businessError(
                ErrorCodeEnum::CONFLICT,
                message: 'Barber already affiliated to a branch',
            );
        }

        $affiliation = BarberAffiliationModel::create([
            'barber_id' => $barber->id,
            'affiliable_id' => $request->validated('affiliable_id'),
            'affiliable_type' => $request->validated('affiliable_type'),
            'commission_rate' => $request->validated('commission_rate'),
            'status' => 'pending',
            'invited_at' => now(),
        ]);

        return $this->created(
            data: new BarberAffiliationResource($affiliation),
            message: 'Affiliation created successfully',
        );
    }
}
