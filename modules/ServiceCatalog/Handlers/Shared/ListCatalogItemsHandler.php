<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Handlers\Shared;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\ServiceCatalog\CQRS\Query\Shared\ListCatalogItemsQuery;
use Modules\ServiceCatalog\Eloquent\Resolvers\Shared\ListCatalogItemsEloquentResolver;

final class ListCatalogItemsHandler
{
    public function __construct(
        private readonly ListCatalogItemsEloquentResolver $resolver,
    ) {}

    public function handle(ListCatalogItemsQuery $payload): LengthAwarePaginator
    {
        return $this->resolver->resolve($payload);
    }
}
