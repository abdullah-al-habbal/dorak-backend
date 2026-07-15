<?php

declare(strict_types=1);

namespace Modules\Booking\Http\Actions;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Modules\Booking\Domain\Commands\CreateBookingCommand;
use Modules\Booking\Enums\BookingStatus;
use Modules\Booking\Http\Requests\CreateBookingRequest;
use Modules\Booking\Http\Resources\BookingResource;
use Modules\Booking\Models\BookingModel;
use Modules\Chair\Enums\ChairStatus;
use Modules\Chair\Models\ChairModel;
use Modules\Core\Http\Actions\BaseApiAction;

final class CreateBookingAction extends BaseApiAction
{
    public function __invoke(CreateBookingRequest $request): JsonResponse
    {
        $command = new CreateBookingCommand(
            ChairId: $request->validated('chair_id'),
            BarberId: $request->validated('barber_id'),
            ClientId: $request->user()->id,
            TimeSlot: Carbon::parse($request->validated('time_slot')),
            ServiceIds: $request->validated('service_ids', []),
            AtHomeLocation: $request->validated('at_home_location'),
        );

        return DB::transaction(function () use ($command): JsonResponse {
            if ($command->ChairId !== null) {
                $chair = ChairModel::findOrFail($command->ChairId);

                if ($chair->status !== ChairStatus::Available) {
                    return $this->error(
                        message: __('booking::messages.chair_not_available'),
                        status: 409,
                    );
                }

                $conflict = BookingModel::where('chair_id', $command->ChairId)
                    ->where('time_slot', $command->TimeSlot)
                    ->whereNotIn('status', ['canceled'])
                    ->lockForUpdate()
                    ->exists();

                if ($conflict) {
                    return $this->error(
                        message: __('booking::messages.double_booking'),
                        status: 409,
                    );
                }
            }

            $bookingData = [
                'client_id' => $command->ClientId,
                'barber_id' => $command->BarberId,
                'time_slot' => $command->TimeSlot,
                'status' => BookingStatus::Confirmed,
            ];

            if ($command->ChairId !== null) {
                $bookingData['chair_id'] = $command->ChairId;
            }

            if ($command->AtHomeLocation !== null) {
                $bookingData['at_home_location'] = $command->AtHomeLocation;
            }

            $booking = BookingModel::create($bookingData);

            if ($command->ServiceIds !== []) {
                $booking->services()->attach($command->ServiceIds);
            }

            $booking->load(['chair.barber', 'barber', 'services']);

            return $this->created(
                data: new BookingResource($booking),
                message: __('booking::messages.booking_created'),
            );
        });
    }
}
