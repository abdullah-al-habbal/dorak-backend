<?php

declare(strict_types=1);

namespace Modules\Branch\Http\Actions\Shared;

use Illuminate\Http\JsonResponse;
use Modules\Branch\Handlers\Shared\GetFloorPlanHandler;
use Modules\Branch\Http\Requests\Shared\GetFloorPlanRequest;
use Modules\Branch\Http\Resources\Shared\ChairResource;
use Modules\Core\Http\Actions\BaseApiAction;

final class GetFloorPlanAction extends BaseApiAction
{
    public function __construct(
        private readonly GetFloorPlanHandler $handler,
    ) {}

    public function __invoke(GetFloorPlanRequest $request, string $branch): JsonResponse
    {
        $branchModel = $this->handler->handle($request->toQuery($branch));

        return $this->ok([
            'branch_id' => $branchModel->id,
            'branch_name' => $branchModel->name,
            'chairs' => ChairResource::collection($branchModel->chairs),
        ]);
    }
}
