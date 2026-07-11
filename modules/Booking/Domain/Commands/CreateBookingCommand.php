<?php

declare(strict_types=1);

namespace Modules\Booking\Domain\Commands;

use Carbon\Carbon;

final readonly class CreateBookingCommand
{
    public function __construct(
        public string $ChairId,
        public ?string $BarberId,
        public string $ClientId,
        public Carbon $TimeSlot,
        public array $ServiceIds,
    ) {}
}
