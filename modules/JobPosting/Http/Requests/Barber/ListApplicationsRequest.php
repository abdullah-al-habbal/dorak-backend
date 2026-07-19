<?php

declare(strict_types=1);

namespace Modules\JobPosting\Http\Requests\Barber;

use Modules\Core\Http\Requests\BaseApiFormRequest;
use Modules\JobPosting\CQRS\Query\Barber\ListApplicationsQuery;

final class ListApplicationsRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
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
