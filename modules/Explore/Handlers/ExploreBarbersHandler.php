<?php

declare(strict_types=1);

namespace Modules\Explore\Handlers;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Explore\CQRS\Query\ExploreBarbersQuery;
use Modules\Explore\Eloquent\Resolvers\ExploreBarbersEloquentResolver;

final class ExploreBarbersHandler
{
    public function __construct(
        private readonly ExploreBarbersEloquentResolver $resolver,
    ) {}

    public function handle(ExploreBarbersQuery $payload): LengthAwarePaginator
    {
        return $this->resolver->resolve($payload);
    }
}
