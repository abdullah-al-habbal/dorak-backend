<?php

declare(strict_types=1);

namespace Modules\Booking\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Modules\Booking\Handlers\Client\ShowBookingHandler;
use Modules\Booking\Http\Requests\Client\ShowBookingRequest;
use Modules\Booking\Http\Resources\Client\BookingResource;
use Modules\Core\Http\Actions\BaseApiAction;

final class ShowBookingAction extends BaseApiAction
{
    public function __construct(
        private readonly ShowBookingHandler $handler,
    ) {}

    public function __invoke(ShowBookingRequest $request, string $booking): JsonResponse
    {
        $result = $this->handler->handle($request->toQuery($booking));

        if (! $result->success) {
            return $this->error(
                message: __('booking::messages.not_own_booking'),
                status: 403,
            );
        }

        return $this->ok(data: new BookingResource($result->booking));
    }
}
