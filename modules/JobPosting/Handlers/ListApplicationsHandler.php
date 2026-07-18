<?php

declare(strict_types=1);

namespace Modules\JobPosting\Handlers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\JobPosting\CQRS\Query\ListApplicationsQuery;
use Modules\JobPosting\Eloquent\Resolvers\ListApplicationsEloquentResolver;

final class ListApplicationsHandler
{
    public function __construct(
        private readonly ListApplicationsEloquentResolver $resolver,
    ) {}

    public function handle(ListApplicationsQuery $payload): LengthAwarePaginator
    {
        return $this->resolver->resolve($payload);
    }
}
