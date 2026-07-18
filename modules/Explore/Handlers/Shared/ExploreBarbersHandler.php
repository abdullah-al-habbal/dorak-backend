<?php

declare(strict_types=1);

namespace Modules\Explore\Handlers\Shared;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Explore\CQRS\Query\Shared\ExploreBarbersQuery;
use Modules\Explore\Eloquent\Resolvers\Shared\ExploreBarbersEloquentResolver;

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
