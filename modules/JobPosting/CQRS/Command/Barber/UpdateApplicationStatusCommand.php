<?php

declare(strict_types=1);

namespace Modules\JobPosting\CQRS\Command\Barber;

use Modules\JobPosting\Enums\ApplicationStatus;

final readonly class UpdateApplicationStatusCommand
{
    public function __construct(
        public string $applicationId,
        public ApplicationStatus $status,
    ) {}
}
