<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Handlers\Client;

use Modules\ServiceCatalog\CQRS\Command\Client\CreateCatalogItemCommand;
use Modules\ServiceCatalog\Models\ServiceCatalogItemModel;

final class CreateCatalogItemHandler
{
    public function handle(CreateCatalogItemCommand $command): ServiceCatalogItemModel
    {
        return ServiceCatalogItemModel::create([
            'category_id' => $command->categoryId,
            'name' => $command->name,
            'description' => $command->description,
            'slug' => $command->slug,
            'sku' => $command->sku,
            'price_range' => $command->priceRange,
            'maintenance_level' => $command->maintenanceLevel,
            'style_period' => $command->stylePeriod,
            'formality' => $command->formality,
            'face_shapes' => $command->faceShapes,
            'hair_textures' => $command->hairTextures,
            'metadata' => $command->metadata,
            'is_active' => $command->isActive,
        ]);
    }
}
