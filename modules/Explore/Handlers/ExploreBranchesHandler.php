<?php

declare(strict_types=1);

namespace Modules\Explore\Handlers;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Explore\CQRS\Query\ExploreBranchesQuery;
use Modules\Explore\Eloquent\Resolvers\ExploreBranchesEloquentResolver;

final class ExploreBranchesHandler
{
    public function __construct(
        private readonly ExploreBranchesEloquentResolver $resolver,
    ) {}

    public function handle(ExploreBranchesQuery $payload): LengthAwarePaginator
    {
        return $this->resolver->resolve($payload);
    }
}
