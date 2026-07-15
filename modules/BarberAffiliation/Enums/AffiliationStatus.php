<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\Enums;

enum AffiliationStatus: string
{
    case Pending = 'pending';
    case Accepted = 'accepted';
    case Rejected = 'rejected';
    case Terminated = 'terminated';

    public function label(): string
    {
        return $this->value;
    }
}
