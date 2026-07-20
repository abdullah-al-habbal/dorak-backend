<?php

declare(strict_types=1);

namespace Modules\Branch\Http\Requests\Shared;

use Modules\Branch\CQRS\Query\Shared\GetFloorPlanQuery;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class GetFloorPlanRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [];
    }

    public function toQuery(string $branchId): GetFloorPlanQuery
    {
        return new GetFloorPlanQuery(
            branchId: $branchId,
        );
    }
}
