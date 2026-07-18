<?php

declare(strict_types=1);

namespace Modules\Booking\ValuesObjects;

use Modules\Booking\Models\BookingModel;

final readonly class CreateBookingResult
{
    public function __construct(
        public BookingModel $booking,
    ) {}
}
