<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\CQRS\Command;

final readonly class UpdateCatalogItemCommand
{
    public function __construct(
        public int $id,
        public ?int $categoryId,
        public ?array $name,
        public ?array $description,
        public ?string $slug,
        public ?string $sku,
        public ?array $priceRange,
        public ?string $maintenanceLevel,
        public ?string $stylePeriod,
        public ?string $formality,
        public ?array $faceShapes,
        public ?array $hairTextures,
        public ?array $metadata,
        public ?bool $isActive,
    ) {}
}
