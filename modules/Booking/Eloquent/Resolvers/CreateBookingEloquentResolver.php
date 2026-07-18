<?php

declare(strict_types=1);

namespace Modules\Booking\Eloquent\Resolvers;

use Modules\Booking\CQRS\Command\CreateBookingCommand;
use Modules\Booking\Enums\BookingStatus;
use Modules\Booking\Models\BookingModel;
use Modules\Chair\Enums\ChairStatus;
use Modules\Chair\Models\ChairModel;


final class CreateBookingEloquentResolver
{
    public function resolve(CreateBookingCommand $command): BookingModel
    {

        if ($command->chairId !== null) {
            $chair = ChairModel::findOrFail($command->chairId);

            if ($chair->status !== ChairStatus::Available) {
                throw new ChairNotAvailableException;
            }

            $conflict = BookingModel::where('chair_id', $command->chairId)
                ->where('time_slot', $command->timeSlot)
                ->whereNotIn('status', ['canceled'])
                ->lockForUpdate()
                ->exists();

            if ($conflict) {
                throw new DoubleBookingException;
            }
        }

        $bookingData = [
            'client_id' => $command->clientId,
            'barber_id' => $command->barberId,
            'time_slot' => $command->timeSlot,
            'status' => BookingStatus::Confirmed,
        ];

        if ($command->chairId !== null) {
            $bookingData['chair_id'] = $command->chairId;
        }

        if ($command->atHomeLocation !== null) {
            $bookingData['at_home_location'] = $command->atHomeLocation;
        }

        $booking = BookingModel::create($bookingData);

        if ($command->serviceIds !== []) {
            $booking->services()->attach($command->serviceIds);
        }

        $booking->load(['chair.barber', 'barber', 'services']);

        return $booking;
    }
}
