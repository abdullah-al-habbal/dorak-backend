<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Handlers;


use Modules\ServiceCatalog\CQRS\Command\UpdateCatalogItemCommand;
use Modules\ServiceCatalog\Models\ServiceCatalogItemModel;

final class UpdateCatalogItemHandler
{
    public function handle(object $payload): ServiceCatalogItemModel
    {
        assert($payload instanceof UpdateCatalogItemCommand);

        $item = ServiceCatalogItemModel::findOrFail($payload->id);

        $data = \array_filter([
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
        ], fn ($v) => $v !== null);

        $item->update($data);

        return $item->fresh();
    }
}
