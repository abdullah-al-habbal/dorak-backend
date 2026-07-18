<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Handlers;


use Modules\ServiceCatalog\CQRS\Command\CreateCatalogItemCommand;
use Modules\ServiceCatalog\Models\ServiceCatalogItemModel;

final class CreateCatalogItemHandler
{
    public function handle(object $payload): ServiceCatalogItemModel
    {
        assert($payload instanceof CreateCatalogItemCommand);

        return ServiceCatalogItemModel::create([
            'category_id' => $payload->categoryId,
            'name' => $payload->name,
            'description' => $payload->description,
            'slug' => $payload->slug,
            'sku' => $payload->sku,
            'price_range' => $payload->priceRange,
            'maintenance_level' => $payload->maintenanceLevel,
            'style_period' => $payload->stylePeriod,
            'formality' => $payload->formality,
            'face_shapes' => $payload->faceShapes,
            'hair_textures' => $payload->hairTextures,
            'metadata' => $payload->metadata,
            'is_active' => $payload->isActive,
        ]);
    }
}
