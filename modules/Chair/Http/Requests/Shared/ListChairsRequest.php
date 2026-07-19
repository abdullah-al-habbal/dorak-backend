<?php

declare(strict_types=1);

namespace Modules\Chair\Http\Requests\Shared;

use Modules\Chair\CQRS\Query\Shared\ListChairsQuery;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class ListChairsRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'branch_id' => ['nullable', 'string'],
            'status' => ['nullable', 'string'],
        ];
    }

    public function toQuery(): ListChairsQuery
    {
        return new ListChairsQuery(
            perPage: (int) ($this->validated('per_page') ?? 20),
            branchId: $this->route('branch') ?? $this->validated('branch_id'),
            status: $this->validated('status'),
        );
    }
}
