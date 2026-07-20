<?php

declare(strict_types=1);

namespace Modules\JobPosting\CQRS\Query\Shared;

final readonly class ShowJobPostingQuery
{
    public function __construct(
        public string $jobId,
    ) {}
}
