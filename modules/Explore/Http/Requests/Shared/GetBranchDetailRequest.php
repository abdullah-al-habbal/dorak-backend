<?php

declare(strict_types=1);

namespace Modules\Explore\Http\Requests\Shared;

use Modules\Core\Http\Requests\BaseApiFormRequest;
use Modules\Explore\CQRS\Query\Shared\GetBranchDetailQuery;

final class GetBranchDetailRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [];
    }

    public function toQuery(string $branchId): GetBranchDetailQuery
    {
        return new GetBranchDetailQuery(
            branchId: $branchId,
        );
    }
}
