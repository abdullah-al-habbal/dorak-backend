<?php

declare(strict_types=1);

namespace Modules\Explore\CQRS\Query\Shared;

final readonly class ExploreBranchesQuery
{
    public function __construct(
        public float $lat,
        public float $lng,
        public float $radius,
        public ?string $universe,
        public int $perPage,
    ) {}
}
