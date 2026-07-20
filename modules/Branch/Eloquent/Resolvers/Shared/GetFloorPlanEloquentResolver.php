<?php

declare(strict_types=1);

namespace Modules\Branch\Eloquent\Resolvers\Shared;

use Modules\Branch\CQRS\Query\Shared\GetFloorPlanQuery;
use Modules\Branch\Models\BranchModel;

final class GetFloorPlanEloquentResolver
{
    public function resolve(GetFloorPlanQuery $query): BranchModel
    {
        $branch = BranchModel::findOrFail($query->branchId);
        $branch->load('chairs.barber');

        return $branch;
    }
}
