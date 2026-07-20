<?php

declare(strict_types=1);

namespace Modules\JobPosting\Http\Requests\Shared;

use Modules\Core\Http\Requests\BaseApiFormRequest;
use Modules\JobPosting\CQRS\Query\Shared\ShowJobPostingQuery;

final class ShowJobPostingRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [];
    }

    public function toQuery(string $jobId): ShowJobPostingQuery
    {
        return new ShowJobPostingQuery(
            jobId: $jobId,
        );
    }
}
