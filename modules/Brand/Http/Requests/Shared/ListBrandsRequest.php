<?php

declare(strict_types=1);

namespace Modules\Brand\Http\Requests\Shared;

use Modules\Brand\CQRS\Query\Shared\ListBrandsQuery;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class ListBrandsRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function toQuery(): ListBrandsQuery
    {
        return new ListBrandsQuery(
            perPage: (int) ($this->validated('per_page') ?? 20),
        );
    }
}
