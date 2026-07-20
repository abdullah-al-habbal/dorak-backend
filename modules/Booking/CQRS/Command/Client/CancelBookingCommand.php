<?php

declare(strict_types=1);

namespace Modules\Booking\CQRS\Command\Client;

final readonly class CancelBookingCommand
{
    public function __construct(
        public string $bookingId,
        public string $clientId,
    ) {}
}
