<?php

declare(strict_types=1);

namespace Modules\Booking\Enums;

enum BookingStatus: string
{
    case Confirmed = 'confirmed';
    case Canceled = 'canceled';
    case Completed = 'completed';

    public function label(): string
    {
        return $this->value;
    }
}
