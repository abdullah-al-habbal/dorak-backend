<?php

declare(strict_types=1);

namespace Modules\JobPosting\Handlers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\JobPosting\CQRS\Query\ListJobPostingsQuery;
use Modules\JobPosting\Eloquent\Resolvers\ListJobPostingsEloquentResolver;

final class ListJobPostingsHandler
{
    public function __construct(
        private readonly ListJobPostingsEloquentResolver $resolver,
    ) {}

    public function handle(ListJobPostingsQuery $payload): LengthAwarePaginator
    {
        return $this->resolver->resolve($payload);
    }
}
