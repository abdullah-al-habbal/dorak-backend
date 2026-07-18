<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Http\Requests;

use Modules\Core\Http\Requests\BaseApiFormRequest;

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
            // todo: use Enum instead of "in"
            'maintenance_level' => ['nullable', 'string', 'in:low,medium,high'],
            'style_period' => ['nullable', 'string', 'in:classic,modern'],
            'formality' => ['nullable', 'string', 'in:casual,formal,both'],
            'face_shapes' => ['nullable', 'array'],
            'face_shapes.*' => ['string'],
            'hair_textures' => ['nullable', 'array'],
            'hair_textures.*' => ['string'],
            'metadata' => ['nullable', 'array'],
            'is_active' => ['boolean'],
        ];
    }
}
