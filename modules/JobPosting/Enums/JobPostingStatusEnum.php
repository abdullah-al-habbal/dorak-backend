<?php

declare(strict_types=1);

namespace Modules\JobPosting\Enums;

enum JobPostingStatusEnum: string
{
    case Open = 'open';
    case Closed = 'closed';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::Open => 'Open',
            self::Closed => 'Closed',
            self::Archived => 'Archived',
        };
    }

    public function isOpen(): bool
    {
        return $this === self::Open;
    }
}
