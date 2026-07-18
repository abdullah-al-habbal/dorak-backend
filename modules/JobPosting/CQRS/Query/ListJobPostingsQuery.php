<?php

declare(strict_types=1);

namespace Modules\JobPosting\CQRS\Query;

final readonly class ListJobPostingsQuery
{
    public function __construct(
        public int $perPage,
    ) {}
}
