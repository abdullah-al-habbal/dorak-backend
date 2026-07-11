<?php
declare(strict_types=1);

namespace Modules\Booking\Http\Actions;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Modules\Booking\Models\BookingModel;
use Modules\Chair\Models\ChairModel;
use Modules\Core\Http\Actions\BaseApiAction;

final class CreateBookingAction extends BaseApiAction
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'chair_id' => ['required', 'string', 'exists:chairs,id'],
            'barber_id' => ['nullable', 'string', 'exists:barbers,id'],
            'time_slot' => ['required', 'date_format:Y-m-d H:i:s', 'after:now'],
            'service_ids' => ['nullable', 'array'],
            'service_ids.*' => ['string', 'exists:offered_services,id'],
        ]);

        $chair = ChairModel::findOrFail($validated['chair_id']);

        if ($chair->status !== 'available') {
            return $this->error(
                message: __('booking::messages.chair_not_available'),
                statusCode: 409,
            );
        }

        $timeSlot = Carbon::parse($validated['time_slot']);

        $conflict = BookingModel::where('chair_id', $validated['chair_id'])
            ->where('time_slot', $timeSlot)
            ->whereNotIn('status', ['cancelled'])
            ->exists();

        if ($conflict) {
            return $this->error(
                message: __('booking::messages.double_booking'),
                statusCode: 409,
            );
        }

        $booking = BookingModel::create([
            'client_id' => $request->user()->id,
            'chair_id' => $validated['chair_id'],
            'barber_id' => $validated['barber_id'] ?? $chair->barber_id,
            'time_slot' => $timeSlot,
            'status' => 'confirmed',
        ]);

        if (!empty($validated['service_ids'])) {
            $booking->services()->attach($validated['service_ids']);
        }

        $booking->load(['chair.barber', 'barber', 'services']);

        return $this->created(
            data: new \Modules\Booking\Http\Resources\BookingResource($booking),
            message: __('booking::messages.booking_created'),
        );
    }
}
