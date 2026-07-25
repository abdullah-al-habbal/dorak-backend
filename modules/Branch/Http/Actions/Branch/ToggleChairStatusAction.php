<?php

declare(strict_types=1);

namespace Modules\Branch\Http\Actions\Branch;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Chair\Enums\ChairStatus;
use Modules\Chair\Events\ChairStatusUpdated;
use Modules\Chair\Models\ChairModel;
use Modules\Core\Enums\SuccessCodeEnum;
use Modules\Core\Http\Actions\BaseApiAction;

final class ToggleChairStatusAction extends BaseApiAction
{
    public function __invoke(Request $request, string $chair): JsonResponse
    {
        $branch = $request->user('branch_api');
        $chairModel = ChairModel::where('branch_id', $branch->id)->findOrFail($chair);

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:available,occupied,maintenance'],
        ]);

        $status = ChairStatus::from($validated['status']);
        $chairModel->update(['status' => $status]);

        event(new ChairStatusUpdated(
            chairId: $chairModel->id,
            branchId: $branch->id,
            status: $status->value,
        ));

        return $this->success(
            data: [
                'id' => $chairModel->id,
                'label' => $chairModel->label,
                'status' => $chairModel->fresh()->status->value,
            ],
            code: SuccessCodeEnum::UPDATED,
        );
    }
}
