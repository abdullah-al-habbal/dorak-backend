<?php
declare(strict_types=1);

namespace Modules\Branch\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\Branch\Models\BranchModel;
use Modules\Branch\Http\Resources\ChairResource;
use Modules\Core\Http\Actions\BaseApiAction;

final class GetFloorPlanAction extends BaseApiAction
{
    public function __invoke(BranchModel $branch): JsonResponse
    {
        $branch->load('chairs.barber');

        return $this->ok([
            'branch_id' => $branch->id,
            'branch_name' => $branch->name,
            'chairs' => ChairResource::collection($branch->chairs),
        ]);
    }
}
