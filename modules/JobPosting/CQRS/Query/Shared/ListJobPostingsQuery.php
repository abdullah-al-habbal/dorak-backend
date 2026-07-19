<?php

declare(strict_types=1);

namespace Modules\JobPosting\CQRS\Query\Shared;

final readonly class ListJobPostingsQuery
{
    public function __construct(
        public int $perPage,
    ) {}
}
