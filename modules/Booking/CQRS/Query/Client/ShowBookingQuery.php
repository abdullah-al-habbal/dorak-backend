<?php

declare(strict_types=1);

namespace Modules\Booking\CQRS\Query\Client;

final readonly class ShowBookingQuery
{
    public function __construct(
        public string $bookingId,
        public string $clientId,
    ) {}
}
