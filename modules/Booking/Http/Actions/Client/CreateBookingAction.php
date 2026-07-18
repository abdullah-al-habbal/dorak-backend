<?php

declare(strict_types=1);

namespace Modules\Booking\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Modules\Booking\Eloquent\Resolvers\Shared\ChairNotAvailableException;
use Modules\Booking\Eloquent\Resolvers\Shared\DoubleBookingException;
use Modules\Booking\Handlers\Client\CreateBookingHandler;
use Modules\Booking\Http\Requests\Client\CreateBookingRequest;
use Modules\Booking\Http\Resources\Client\BookingResource;
use Modules\Core\Http\Actions\BaseApiAction;

final class CreateBookingAction extends BaseApiAction
{
    public function __construct(
        private readonly CreateBookingHandler $handler,
    ) {}

    public function __invoke(CreateBookingRequest $request): JsonResponse
    {
        try {
            $result = $this->handler->handle($request->toCommand());

            return $this->created(
                data: new BookingResource($result->booking),
                message: __('booking::messages.booking_created'),
            );
        } catch (ChairNotAvailableException) {
            return $this->error(
                message: __('booking::messages.chair_not_available'),
                status: 409,
            );
        } catch (DoubleBookingException) {
            return $this->error(
                message: __('booking::messages.double_booking'),
                status: 409,
            );
        }
    }
}
