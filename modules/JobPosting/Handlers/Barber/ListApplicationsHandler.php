<?php

declare(strict_types=1);

namespace Modules\JobPosting\Handlers\Barber;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\JobPosting\CQRS\Query\Barber\ListApplicationsQuery;
use Modules\JobPosting\Eloquent\Resolvers\Barber\ListApplicationsEloquentResolver;

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
