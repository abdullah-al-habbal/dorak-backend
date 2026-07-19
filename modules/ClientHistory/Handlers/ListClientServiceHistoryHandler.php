<?php

declare(strict_types=1);

namespace Modules\ClientHistory\Handlers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\ClientHistory\CQRS\Query\ListClientServiceHistoryQuery;
use Modules\ClientHistory\Eloquent\Resolvers\ListClientServiceHistoryEloquentResolver;

final class ListClientServiceHistoryHandler
{
    public function __construct(
        private readonly ListClientServiceHistoryEloquentResolver $resolver,
    ) {}

    public function handle(ListClientServiceHistoryQuery $query): LengthAwarePaginator
    {
        return $this->resolver->resolve($query);
    }
}
