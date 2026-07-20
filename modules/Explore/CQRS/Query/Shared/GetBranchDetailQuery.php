<?php

declare(strict_types=1);

namespace Modules\Explore\CQRS\Query\Shared;

final readonly class GetBranchDetailQuery
{
    public function __construct(
        public string $branchId,
    ) {}
}
