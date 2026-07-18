<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\CQRS\Command;

final readonly class DeleteCatalogItemCommand
{
    public function __construct(
        public int $id,
    ) {}
}
