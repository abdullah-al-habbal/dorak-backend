<?php

declare(strict_types=1);

namespace Modules\Review\Http\Requests\Shared;

use Modules\Core\Http\Requests\BaseApiFormRequest;
use Modules\Review\CQRS\Query\Shared\GetBranchReviewsQuery;

final class GetBranchReviewsRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function toQuery(): GetBranchReviewsQuery
    {
        return new GetBranchReviewsQuery(
            branchId: $this->route('branch'),
            perPage: (int) ($this->validated('per_page') ?? 20),
        );
    }
}
