<?php

declare(strict_types=1);

namespace Modules\JobPosting\ValuesObjects;

use Modules\JobPosting\Models\ApplicationModel;

final readonly class ApplyForJobResult
{
    private function __construct(
        public bool $success,
        public ?ApplicationModel $application,
        public ?string $errorCode,
    ) {}

    public static function success(ApplicationModel $application): self
    {
        return new self(true, $application, null);
    }

    public static function notOpen(): self
    {
        return new self(false, null, 'not_open');
    }

    public static function alreadyApplied(): self
    {
        return new self(false, null, 'already_applied');
    }
}
