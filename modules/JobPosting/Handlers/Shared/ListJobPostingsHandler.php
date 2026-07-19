<?php

declare(strict_types=1);

namespace Modules\JobPosting\Handlers\Shared;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\JobPosting\CQRS\Query\Shared\ListJobPostingsQuery;
use Modules\JobPosting\Eloquent\Resolvers\Shared\ListJobPostingsEloquentResolver;

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
