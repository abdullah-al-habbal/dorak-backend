<?php

declare(strict_types=1);

namespace Modules\JobPosting\Handlers\Shared;

use Modules\JobPosting\CQRS\Query\Shared\ShowJobPostingQuery;
use Modules\JobPosting\Eloquent\Resolvers\Shared\ShowJobPostingEloquentResolver;
use Modules\JobPosting\Models\JobPostingModel;

final class ShowJobPostingHandler
{
    public function __construct(
        private readonly ShowJobPostingEloquentResolver $resolver,
    ) {}

    public function handle(ShowJobPostingQuery $query): JobPostingModel
    {
        return $this->resolver->resolve($query);
    }
}
