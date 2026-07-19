<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Http\Requests\Client;

use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\BaseApiFormRequest;
use Modules\ServiceCatalog\CQRS\Command\Client\UpdateCatalogItemCommand;
use Modules\ServiceCatalog\Enums\FormalityEnum;
use Modules\ServiceCatalog\Enums\MaintenanceLevelEnum;
use Modules\ServiceCatalog\Enums\StylePeriodEnum;

final class UpdateCatalogItemRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'category_id' => ['sometimes', 'integer', 'exists:service_catalog_categories,id'],
            'name.en' => ['sometimes', 'string', 'max:255'],
            'name.ar' => ['sometimes', 'string', 'max:255'],
            'description.en' => ['nullable', 'string', 'max:5000'],
            'description.ar' => ['nullable', 'string', 'max:5000'],
            'slug' => ['sometimes', 'string', 'max:255', Rule::unique('service_catalog_items', 'slug')->ignore($id)],
            'sku' => ['nullable', 'string', 'max:100', Rule::unique('service_catalog_items', 'sku')->ignore($id)],
            'price_range' => ['sometimes', 'array'],
            'price_range.min' => ['required_with:price_range', 'numeric', 'min:0'],
            'price_range.max' => ['required_with:price_range', 'numeric', 'min:0'],
            'price_range.currency' => ['required_with:price_range', 'string', 'size:3'],
            'maintenance_level' => ['nullable', Rule::enum(MaintenanceLevelEnum::class)],
            'style_period' => ['nullable', Rule::enum(StylePeriodEnum::class)],
            'formality' => ['nullable', Rule::enum(FormalityEnum::class)],
            'face_shapes' => ['sometimes', 'array'],
            'face_shapes.*' => ['string'],
            'hair_textures' => ['sometimes', 'array'],
            'hair_textures.*' => ['string'],
            'metadata' => ['nullable', 'array'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function toCommand(int $id): UpdateCatalogItemCommand
    {
        $data = $this->validated();

        return new UpdateCatalogItemCommand(
            id: $id,
            categoryId: $data['category_id'] ?? null,
            name: $data['name'] ?? null,
            description: $data['description'] ?? null,
            slug: $data['slug'] ?? null,
            sku: $data['sku'] ?? null,
            priceRange: $data['price_range'] ?? null,
            maintenanceLevel: isset($data['maintenance_level']) ? MaintenanceLevelEnum::from($data['maintenance_level']) : null,
            stylePeriod: isset($data['style_period']) ? StylePeriodEnum::from($data['style_period']) : null,
            formality: isset($data['formality']) ? FormalityEnum::from($data['formality']) : null,
            faceShapes: $data['face_shapes'] ?? null,
            hairTextures: $data['hair_textures'] ?? null,
            metadata: $data['metadata'] ?? null,
            isActive: $data['is_active'] ?? null,
        );
    }
}
