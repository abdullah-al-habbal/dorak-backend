<?php

declare(strict_types=1);

namespace Modules\Activation\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\Activation\Http\Requests\ToggleActivationRequest;
use Modules\Activation\Http\Resources\ActivationLogResource;
use Modules\Activation\Models\ActivationLogModel;
use Modules\Barber\Models\BarberModel;
use Modules\Core\Http\Actions\BaseApiAction;

final class ActivateAction extends BaseApiAction
{
    public function __invoke(ToggleActivationRequest $request, string $barber): JsonResponse
    {
        $barber = BarberModel::findOrFail($barber);

        $log = ActivationLogModel::create([
            'activable_id' => $barber->id,
            'activable_type' => $barber->getMorphClass(),
            'status' => 'enabled',
            'reason' => $request->validated('reason'),
            'activated_at' => now(),
        ]);

        return $this->created(
            data: new ActivationLogResource($log),
            message: 'Barber activated successfully',
        );
    }
}
