<?php

declare(strict_types=1);

namespace Modules\Booking\CQRS\Command\Client;

use Carbon\Carbon;

final readonly class CreateBookingCommand
{
    public function __construct(
        public ?string $chairId,
        public ?string $barberId,
        public string $clientId,
        public Carbon $timeSlot,
        public array $serviceIds,
        public ?array $atHomeLocation,
    ) {}
}
