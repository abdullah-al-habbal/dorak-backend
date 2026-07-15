<?php

declare(strict_types=1);

namespace Modules\Chair\Http\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Chair\Http\Resources\ChairResource;
use Modules\Chair\Models\ChairModel;
use Modules\Core\Http\Actions\BaseApiAction;

final class ShowChairAction extends BaseApiAction
{
    public function __invoke(Request $request, string $chair): JsonResponse
    {
        $chair = ChairModel::with(['branch', 'barber'])->findOrFail($chair);

        return $this->ok(data: new ChairResource($chair));
    }
}
