<?php

declare(strict_types=1);

namespace Modules\Booking\Eloquent\Resolvers\Client;

use Modules\Booking\CQRS\Command\Client\CancelBookingCommand;
use Modules\Booking\Enums\BookingStatus;
use Modules\Booking\Models\BookingModel;
use Modules\Booking\ValuesObjects\CancelBookingResult;

final class CancelBookingEloquentResolver
{
    public function resolve(CancelBookingCommand $command): CancelBookingResult
    {
        $booking = BookingModel::with(['chair.barber', 'barber', 'services'])
            ->findOrFail($command->bookingId);

        if ($booking->client_id !== $command->clientId) {
            return CancelBookingResult::notOwnBooking();
        }

        if ($booking->status !== BookingStatus::Confirmed) {
            return CancelBookingResult::invalidStatus();
        }

        $booking->update(['status' => BookingStatus::Canceled]);

        return CancelBookingResult::success($booking->fresh());
    }
}
