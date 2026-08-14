<?php

declare(strict_types=1);

namespace Modules\Booking\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Modules\Booking\Handlers\Client\CancelBookingHandler;
use Modules\Booking\Http\Requests\Client\CancelBookingRequest;
use Modules\Booking\Http\Resources\Client\BookingResource;
use Modules\Core\Http\Actions\BaseApiAction;

final class CancelBookingAction extends BaseApiAction
{
    public function __construct(
        private readonly CancelBookingHandler $handler,
    ) {}

    public function __invoke(CancelBookingRequest $request, string $booking): JsonResponse
    {
        $result = $this->handler->handle($request->toCommand($booking));

        if (! $result->success) {
            $status = match ($result->errorCode) {
                'not_own_booking' => 403,
                default => 422,
            };

            return $this->error(
                message: __('booking::messages.'.$result->errorCode),
                status: $status,
            );
        }

        return $this->ok(
            data: new BookingResource($result->booking),
            message: __('booking::messages.canceled'),
        );
    }
}
