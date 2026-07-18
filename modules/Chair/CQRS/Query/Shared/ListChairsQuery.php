<?php

declare(strict_types=1);

namespace Modules\Chair\CQRS\Query\Shared;

final readonly class ListChairsQuery
{
    public function __construct(
        public int $perPage,
        public ?string $branchId,
        public ?string $status,
    ) {}
}
