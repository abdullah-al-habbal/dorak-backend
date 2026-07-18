<?php

declare(strict_types=1);

namespace Modules\Chair\Handlers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Chair\CQRS\Query\ListChairsQuery;
use Modules\Chair\Eloquent\Resolvers\ListChairsEloquentResolver;

final class ListChairsHandler
{
    public function __construct(
        private readonly ListChairsEloquentResolver $resolver,
    ) {}

    public function handle(ListChairsQuery $payload): LengthAwarePaginator
    {
        return $this->resolver->resolve($payload);
    }
}
