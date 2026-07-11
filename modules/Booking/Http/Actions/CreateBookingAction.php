<?php

declare(strict_types=1);

namespace Modules\Booking\Http\Actions;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Modules\Booking\Domain\Commands\CreateBookingCommand;
use Modules\Booking\Http\Requests\CreateBookingRequest;
use Modules\Booking\Http\Resources\BookingResource;
use Modules\Booking\Models\BookingModel;
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
        );

        $chair = ChairModel::findOrFail($command->ChairId);

        if ($chair->status !== 'available') {
            return $this->error(
                message: __('booking::messages.chair_not_available'),
                statusCode: 409,
            );
        }

        $conflict = BookingModel::where('chair_id', $command->ChairId)
            ->where('time_slot', $command->TimeSlot)
            ->whereNotIn('status', ['cancelled'])
            ->exists();

        if ($conflict) {
            return $this->error(
                message: __('booking::messages.double_booking'),
                statusCode: 409,
            );
        }

        $booking = BookingModel::create([
            'client_id' => $command->ClientId,
            'chair_id' => $command->ChairId,
            'barber_id' => $command->BarberId ?? $chair->barber_id,
            'time_slot' => $command->TimeSlot,
            'status' => 'confirmed',
        ]);

        if ($command->ServiceIds !== []) {
            $booking->services()->attach($command->ServiceIds);
        }

        $booking->load(['chair.barber', 'barber', 'services']);

        return $this->created(
            data: new BookingResource($booking),
            message: __('booking::messages.booking_created'),
        );
    }
}
