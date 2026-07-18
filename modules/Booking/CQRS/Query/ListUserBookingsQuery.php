<?php

declare(strict_types=1);

namespace Modules\Booking\CQRS\Query;

use Modules\Booking\Enums\BookingFilterStatus;

final readonly class ListUserBookingsQuery
{
    public function __construct(
        public string $clientId,
        public ?BookingFilterStatus $filterStatus,
    ) {}
}
