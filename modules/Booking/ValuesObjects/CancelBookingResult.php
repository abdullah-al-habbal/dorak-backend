<?php

declare(strict_types=1);

namespace Modules\Booking\ValuesObjects;

use Modules\Booking\Models\BookingModel;

final readonly class CancelBookingResult
{
    private function __construct(
        public bool $success,
        public ?BookingModel $booking,
        public ?string $errorCode,
    ) {}

    public static function success(BookingModel $booking): self
    {
        return new self(true, $booking, null);
    }

    public static function notOwnBooking(): self
    {
        return new self(false, null, 'not_own_booking');
    }

    public static function invalidStatus(): self
    {
        return new self(false, null, 'cancel_invalid_status');
    }
}
