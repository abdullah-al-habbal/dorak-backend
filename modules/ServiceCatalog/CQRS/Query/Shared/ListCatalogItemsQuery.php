<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\CQRS\Query\Shared;

final readonly class ListCatalogItemsQuery
{
    public function __construct(
        public ?int $categoryId,
        public ?string $search,
        public int $perPage,
    ) {}
}
