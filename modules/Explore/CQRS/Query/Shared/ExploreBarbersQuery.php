<?php

declare(strict_types=1);

namespace Modules\Explore\CQRS\Query\Shared;

final readonly class ExploreBarbersQuery
{
    public function __construct(
        public float $latitude,
        public float $longitude,
        public float $radius,
        public int $perPage,
        public ?array $catalogItemIds = null,
        public ?bool $availableNow = null,
        public ?float $priceRangeMin = null,
        public ?float $priceRangeMax = null,
        public ?float $ratingMin = null,
        public ?string $faceShapeCompatible = null,
        public ?string $clientId = null,
    ) {}
}
