<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Handlers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\ClientInteraction\CQRS\Query\ListFavoritesQuery;
use Modules\ClientInteraction\Eloquent\Resolvers\ListFavoritesEloquentResolver;

final class ListFavoritesHandler
{
    public function __construct(
        private readonly ListFavoritesEloquentResolver $resolver,
    ) {}

    public function handle(ListFavoritesQuery $query): LengthAwarePaginator
    {
        return $this->resolver->resolve($query);
    }
}
