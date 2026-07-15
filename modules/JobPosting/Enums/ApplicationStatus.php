<?php

declare(strict_types=1);

namespace Modules\JobPosting\Enums;

enum ApplicationStatus: string
{
    case Submitted = 'submitted';
    case Reviewed = 'reviewed';
    case Accepted = 'accepted';
    case Rejected = 'rejected';

    public function label(): string
    {
        return $this->value;
    }
}
