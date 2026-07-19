<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Handlers;

use Illuminate\Database\Eloquent\Collection;
use Modules\ClientInteraction\CQRS\Query\ListSavedFiltersQuery;
use Modules\ClientInteraction\Eloquent\Resolvers\ListSavedFiltersEloquentResolver;

final class ListSavedFiltersHandler
{
    public function __construct(
        private readonly ListSavedFiltersEloquentResolver $resolver,
    ) {}

    public function handle(ListSavedFiltersQuery $query): Collection
    {
        return $this->resolver->resolve($query);
    }
}
