<?php

declare(strict_types=1);

namespace Modules\Booking\Eloquent\Resolvers\Client;

use Modules\Booking\CQRS\Query\Client\ShowBookingQuery;
use Modules\Booking\Models\BookingModel;
use Modules\Booking\ValuesObjects\ShowBookingResult;

final class ShowBookingEloquentResolver
{
    public function resolve(ShowBookingQuery $query): ShowBookingResult
    {
        $booking = BookingModel::with(['chair.barber', 'barber', 'services'])
            ->findOrFail($query->bookingId);

        if ($booking->client_id !== $query->clientId) {
            return ShowBookingResult::notOwnBooking();
        }

        return ShowBookingResult::success($booking);
    }
}
