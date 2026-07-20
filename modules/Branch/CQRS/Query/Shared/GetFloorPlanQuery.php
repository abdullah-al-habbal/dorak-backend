<?php

declare(strict_types=1);

namespace Modules\Branch\CQRS\Query\Shared;

final readonly class GetFloorPlanQuery
{
    public function __construct(
        public string $branchId,
    ) {}
}
