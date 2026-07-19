<?php

declare(strict_types=1);

namespace Modules\ClientHistory\Eloquent\Resolvers;

use Modules\Booking\Enums\BookingStatus;
use Modules\Booking\Models\BookingModel;
use Modules\ClientHistory\CQRS\Command\RebookFromHistoryCommand;
use Modules\ClientHistory\Models\ClientServiceHistoryModel;

final class RebookFromHistoryEloquentResolver
{
    public function resolve(RebookFromHistoryCommand $command): BookingModel
    {
        $history = ClientServiceHistoryModel::findOrFail($command->historyId);

        $booking = BookingModel::create([
            'client_id' => $command->clientId,
            'barber_id' => $history->barber_id,
            'chair_id' => $history->booking?->chair_id,
            'time_slot' => $command->timeSlot,
            'status' => BookingStatus::Confirmed,
        ]);

        if ($history->offered_service_id !== null) {
            $booking->services()->attach($history->offered_service_id);
        }

        $booking->load(['chair.barber', 'barber', 'services']);

        return $booking;
    }
}
