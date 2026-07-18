<?php

declare(strict_types=1);

namespace Modules\JobPosting\Http\Requests;

use Modules\Core\Http\Requests\BaseApiFormRequest;
use Modules\JobPosting\CQRS\Query\ListApplicationsQuery;

final class ListApplicationsRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'barber_id' => ['nullable', 'string'],
            'status' => ['nullable', 'string'],
        ];
    }

    public function toQuery(): ListApplicationsQuery
    {
        return new ListApplicationsQuery(
            perPage: (int) ($this->validated('per_page') ?? 20),
            barberId: $this->validated('barber_id'),
            status: $this->validated('status'),
        );
    }
}
