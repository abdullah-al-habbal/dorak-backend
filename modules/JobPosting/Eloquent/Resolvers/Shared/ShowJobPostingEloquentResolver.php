<?php

declare(strict_types=1);

namespace Modules\JobPosting\Eloquent\Resolvers\Shared;

use Modules\JobPosting\CQRS\Query\Shared\ShowJobPostingQuery;
use Modules\JobPosting\Models\JobPostingModel;

final class ShowJobPostingEloquentResolver
{
    public function resolve(ShowJobPostingQuery $query): JobPostingModel
    {
        return JobPostingModel::with('branch')->findOrFail($query->jobId);
    }
}
