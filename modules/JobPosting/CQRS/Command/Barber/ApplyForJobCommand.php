<?php

declare(strict_types=1);

namespace Modules\JobPosting\CQRS\Command\Barber;

final readonly class ApplyForJobCommand
{
    public function __construct(
        public string $jobPostingId,
        public string $barberId,
        public string $barberName,
        public string $barberEmail,
        public bool $isFreelancer,
    ) {}
}
