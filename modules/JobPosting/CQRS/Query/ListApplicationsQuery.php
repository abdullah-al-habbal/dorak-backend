<?php

declare(strict_types=1);

namespace Modules\JobPosting\CQRS\Query;

final readonly class ListApplicationsQuery
{
    public function __construct(
        public int $perPage,
        public ?string $barberId,
        public ?string $status,
    ) {}
}
