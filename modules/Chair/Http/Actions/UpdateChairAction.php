<?php

declare(strict_types=1);

namespace Modules\Chair\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\Chair\Http\Requests\UpdateChairRequest;
use Modules\Chair\Http\Resources\ChairResource;
use Modules\Chair\Models\ChairModel;
use Modules\Core\Http\Actions\BaseApiAction;

final class UpdateChairAction extends BaseApiAction
{
    public function __invoke(UpdateChairRequest $request, string $chair): JsonResponse
    {
        $chair = ChairModel::with(['branch', 'barber'])->findOrFail($chair);

        $chair->update($request->validated());

        $chair->load(['branch', 'barber']);

        return $this->ok(
            data: new ChairResource($chair),
            message: 'Chair updated successfully',
        );
    }
}
