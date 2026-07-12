<?php

declare(strict_types=1);

namespace Modules\Booking\Http\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Booking\Http\Resources\BookingResource;
use Modules\Booking\Models\BookingModel;
use Modules\Core\Http\Actions\BaseApiAction;

final class ShowBookingAction extends BaseApiAction
{
    public function __invoke(Request $request, string $booking): JsonResponse
    {
        $booking = BookingModel::with(['chair.barber', 'barber', 'services'])->findOrFail($booking);

        if ($booking->client_id !== $request->user()->id) {
            return $this->error(message: __('booking::messages.not_own_booking'), statusCode: 403);
        }

        return $this->ok(data: new BookingResource($booking));
    }
}
