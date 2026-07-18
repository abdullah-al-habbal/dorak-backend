<?php

declare(strict_types=1);

namespace Modules\Booking\Enums;

enum BookingFilterStatus: string
{
    case Upcoming = 'upcoming';
    case Past = 'past';
}
