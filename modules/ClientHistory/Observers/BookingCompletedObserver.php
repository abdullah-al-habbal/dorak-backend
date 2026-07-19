<?php

declare(strict_types=1);

namespace Modules\ClientHistory\Observers;

use Modules\Booking\Enums\BookingStatus;
use Modules\Booking\Models\BookingModel;
use Modules\ClientHistory\CQRS\Command\CreateClientServiceHistoryCommand;
use Modules\ClientHistory\Handlers\CreateClientServiceHistoryHandler;

final class BookingCompletedObserver
{
    public function __construct(
        private readonly CreateClientServiceHistoryHandler $handler,
    ) {}

    public function updated(BookingModel $booking): void
    {
        if (! $booking->wasChanged('status')) {
            return;
        }

        if ($booking->status !== BookingStatus::Completed) {
            return;
        }

        $branchId = $booking->chair?->branch_id;
        $firstService = $booking->services->first();

        $command = new CreateClientServiceHistoryCommand(
            clientId: $booking->client_id,
            bookingId: $booking->id,
            barberId: $booking->barber_id,
            branchId: $branchId,
            offeredServiceId: $firstService?->id,
            catalogItemId: $firstService?->catalog_item_id,
            performedAt: $booking->time_slot,
        );

        $this->handler->handle($command);
    }
}
