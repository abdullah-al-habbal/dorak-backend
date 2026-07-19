<?php

declare(strict_types=1);

namespace Modules\Explore\Http\Requests\Shared;

use Illuminate\Validation\Rule;
use Modules\Client\Enums\UniverseEnum;
use Modules\Core\Http\Requests\BaseApiFormRequest;
use Modules\Explore\CQRS\Query\Shared\ExploreBranchesQuery;

final class ExploreBranchesRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
            'radius' => ['required', 'numeric', 'min:0'],
            'universe' => ['required', Rule::enum(UniverseEnum::class)],
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

    public function toQuery(): ExploreBranchesQuery
    {
        $priceRange = $this->validated('price_range');

        return new ExploreBranchesQuery(
            lat: (float) $this->validated('lat'),
            lng: (float) $this->validated('lng'),
            radius: (float) $this->validated('radius'),
            universe: UniverseEnum::from($this->validated('universe')),
            perPage: (int) ($this->validated('per_page') ?? 20),
            catalogItemIds: $this->validated('catalog_item_ids'),
            availableNow: $this->validated('available_now'),
            priceRangeMin: isset($priceRange['min']) ? (float) $priceRange['min'] : null,
            priceRangeMax: isset($priceRange['max']) ? (float) $priceRange['max'] : null,
            ratingMin: $this->validated('rating_min') !== null ? (float) $this->validated('rating_min') : null,
            faceShapeCompatible: $this->validated('face_shape_compatible'),
        );
    }
}
