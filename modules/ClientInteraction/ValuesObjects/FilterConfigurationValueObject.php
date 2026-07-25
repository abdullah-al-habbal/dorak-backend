<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\ValuesObjects;

final readonly class FilterConfigurationValueObject
{
    public function __construct(
        public ?string $universe = null,
        public ?float $radius = null,
        public ?float $latitude = null,
        public ?float $longitude = null,
        public ?array $catalogItemIds = null,
        public ?bool $availableNow = null,
        public ?float $priceMin = null,
        public ?float $priceMax = null,
        public ?float $ratingMin = null,
        public ?string $faceShape = null,
        public ?string $sortBy = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            universe: $data['universe'] ?? null,
            radius: isset($data['radius']) ? (float) $data['radius'] : null,
            latitude: isset($data['latitude']) ? (float) $data['latitude'] : null,
            longitude: isset($data['longitude']) ? (float) $data['longitude'] : null,
            catalogItemIds: $data['catalog_item_ids'] ?? null,
            availableNow: $data['available_now'] ?? null,
            priceMin: isset($data['price_min']) ? (float) $data['price_min'] : null,
            priceMax: isset($data['price_max']) ? (float) $data['price_max'] : null,
            ratingMin: isset($data['rating_min']) ? (float) $data['rating_min'] : null,
            faceShape: $data['face_shape'] ?? null,
            sortBy: $data['sort_by'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'universe' => $this->universe,
            'radius' => $this->radius,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'catalog_item_ids' => $this->catalogItemIds,
            'available_now' => $this->availableNow,
            'price_min' => $this->priceMin,
            'price_max' => $this->priceMax,
            'rating_min' => $this->ratingMin,
            'face_shape' => $this->faceShape,
            'sort_by' => $this->sortBy,
        ], fn ($v) => $v !== null);
    }
}
