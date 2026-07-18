<?php

declare(strict_types=1);

namespace Modules\Review\CQRS\Query\Shared;

final readonly class GetBranchReviewsQuery
{
    public function __construct(
        public string $branchId,
        public int $perPage,
    ) {}
}
