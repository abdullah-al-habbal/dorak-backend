<?php

declare(strict_types=1);

namespace Modules\Explore\CQRS\Query;

final readonly class ExploreBarbersQuery
{
    public function __construct(
        public float $lat,
        public float $lng,
        public float $radius,
        public int $perPage,
    ) {}
}
