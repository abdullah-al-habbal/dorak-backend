<?php

declare(strict_types=1);

namespace Modules\Explore\CQRS\Query\Shared;

use Modules\Client\Enums\UniverseEnum;

final readonly class ExploreBranchesQuery
{
    public function __construct(
        public float $lat,
        public float $lng,
        public float $radius,
        public UniverseEnum $universe,
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
