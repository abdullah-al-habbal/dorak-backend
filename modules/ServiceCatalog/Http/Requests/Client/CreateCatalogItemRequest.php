<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Http\Requests\Client;

use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\BaseApiFormRequest;
use Modules\ServiceCatalog\CQRS\Command\Client\CreateCatalogItemCommand;
use Modules\ServiceCatalog\Enums\FormalityEnum;
use Modules\ServiceCatalog\Enums\MaintenanceLevelEnum;
use Modules\ServiceCatalog\Enums\StylePeriodEnum;

final class CreateCatalogItemRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'integer', 'exists:service_catalog_categories,id'],
            'name.en' => ['required', 'string', 'max:255'],
            'name.ar' => ['required', 'string', 'max:255'],
            'description.en' => ['nullable', 'string', 'max:5000'],
            'description.ar' => ['nullable', 'string', 'max:5000'],
            'slug' => ['required', 'string', 'max:255', 'unique:service_catalog_items,slug'],
            'sku' => ['nullable', 'string', 'max:100', 'unique:service_catalog_items,sku'],
            'price_range' => ['nullable', 'array'],
            'price_range.min' => ['required_with:price_range', 'numeric', 'min:0'],
            'price_range.max' => ['required_with:price_range', 'numeric', 'min:0'],
            'price_range.currency' => ['required_with:price_range', 'string', 'size:3'],
            'maintenance_level' => ['nullable', Rule::enum(MaintenanceLevelEnum::class)],
            'style_period' => ['nullable', Rule::enum(StylePeriodEnum::class)],
            'formality' => ['nullable', Rule::enum(FormalityEnum::class)],
            'face_shapes' => ['nullable', 'array'],
            'face_shapes.*' => ['string'],
            'hair_textures' => ['nullable', 'array'],
            'hair_textures.*' => ['string'],
            'metadata' => ['nullable', 'array'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    public function toCommand(): CreateCatalogItemCommand
    {
        $data = $this->validated();

        return new CreateCatalogItemCommand(
            categoryId: (int) $data['category_id'],
            name: $data['name'],
            description: $data['description'] ?? null,
            slug: $data['slug'],
            sku: $data['sku'] ?? null,
            priceRange: $data['price_range'] ?? null,
            maintenanceLevel: isset($data['maintenance_level']) ? MaintenanceLevelEnum::from($data['maintenance_level']) : null,
            stylePeriod: isset($data['style_period']) ? StylePeriodEnum::from($data['style_period']) : null,
            formality: isset($data['formality']) ? FormalityEnum::from($data['formality']) : null,
            faceShapes: $data['face_shapes'] ?? null,
            hairTextures: $data['hair_textures'] ?? null,
            metadata: $data['metadata'] ?? null,
            isActive: (bool) $data['is_active'],
        );
    }
}
