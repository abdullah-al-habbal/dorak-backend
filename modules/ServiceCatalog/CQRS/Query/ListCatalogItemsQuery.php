<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\CQRS\Query;

final readonly class ListCatalogItemsQuery
{
    public function __construct(
        public ?int $categoryId,
        public ?string $search,
        public int $perPage,
    ) {}
}
