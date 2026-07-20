<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\CQRS\Command\Client;

use Modules\ServiceCatalog\Enums\FormalityEnum;
use Modules\ServiceCatalog\Enums\MaintenanceLevelEnum;
use Modules\ServiceCatalog\Enums\StylePeriodEnum;

final readonly class CreateCatalogItemCommand
{
    public function __construct(
        public int $categoryId,
        public array $name,
        public ?array $description,
        public string $slug,
        public ?string $sku,
        public ?array $priceRange,
        public ?MaintenanceLevelEnum $maintenanceLevel,
        public ?StylePeriodEnum $stylePeriod,
        public ?FormalityEnum $formality,
        public ?array $faceShapes,
        public ?array $hairTextures,
        public ?array $metadata,
        public bool $isActive,
    ) {}
}
