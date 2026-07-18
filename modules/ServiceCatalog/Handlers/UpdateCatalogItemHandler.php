<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Handlers;


use Modules\ServiceCatalog\CQRS\Command\UpdateCatalogItemCommand;
use Modules\ServiceCatalog\Models\ServiceCatalogItemModel;

final class UpdateCatalogItemHandler
{
    public function handle(UpdateCatalogItemCommand $command): ServiceCatalogItemModel
    {
        $item = ServiceCatalogItemModel::findOrFail($command->id);

        $data = array_filter([
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
        ], fn ($v) => $v !== null);

        $item->update($data);

        return $item->fresh();
    }
}
