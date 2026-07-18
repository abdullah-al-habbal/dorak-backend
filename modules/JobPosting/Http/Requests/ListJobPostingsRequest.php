<?php

declare(strict_types=1);

namespace Modules\JobPosting\Http\Requests;

use Modules\Core\Http\Requests\BaseApiFormRequest;
use Modules\JobPosting\CQRS\Query\ListJobPostingsQuery;

final class ListJobPostingsRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function toQuery(): ListJobPostingsQuery
    {
        return new ListJobPostingsQuery(
            perPage: (int) ($this->validated('per_page') ?? 20),
        );
    }
}
