<?php

declare(strict_types=1);

namespace Modules\Explore\Http\Requests\Shared;

use Modules\Core\Http\Requests\BaseApiFormRequest;
use Modules\Explore\CQRS\Query\Shared\ExploreBarbersQuery;

final class ExploreBarbersRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
            'radius' => ['required', 'numeric', 'min:0'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'catalog_item_ids' => ['sometimes', 'array'],
            'catalog_item_ids.*' => ['integer', 'exists:service_catalog_items,id'],
            'available_now' => ['sometimes', 'boolean'],
            'price_range' => ['sometimes', 'array:min,max'],
            'price_range.min' => ['numeric', 'min:0'],
            'price_range.max' => ['numeric', 'min:0'],
            'rating_min' => ['sometimes', 'numeric', 'min:0', 'max:5'],
            'face_shape_compatible' => ['sometimes', 'string'],
        ];
    }

    public function toQuery(): ExploreBarbersQuery
    {
        $priceRange = $this->validated('price_range');

        return new ExploreBarbersQuery(
            lat: (float) $this->validated('lat'),
            lng: (float) $this->validated('lng'),
            radius: (float) $this->validated('radius'),
            perPage: (int) ($this->validated('per_page') ?? 20),
            catalogItemIds: $this->validated('catalog_item_ids'),
            availableNow: $this->boolean('available_now'),
            priceRangeMin: isset($priceRange['min']) ? (float) $priceRange['min'] : null,
            priceRangeMax: isset($priceRange['max']) ? (float) $priceRange['max'] : null,
            ratingMin: $this->validated('rating_min') !== null ? (float) $this->validated('rating_min') : null,
            faceShapeCompatible: $this->validated('face_shape_compatible'),
            clientId: $this->user()?->id,
        );
    }
}
