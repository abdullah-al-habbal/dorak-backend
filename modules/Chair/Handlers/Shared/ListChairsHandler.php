<?php

declare(strict_types=1);

namespace Modules\Chair\Handlers\Shared;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Chair\CQRS\Query\Shared\ListChairsQuery;
use Modules\Chair\Eloquent\Resolvers\Shared\ListChairsEloquentResolver;

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
