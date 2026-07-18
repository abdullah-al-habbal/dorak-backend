<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\CQRS\Query;

final readonly class GetCatalogItemQuery
{
    public function __construct(
        public int $id,
    ) {}
}
