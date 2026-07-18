<?php

declare(strict_types=1);

namespace Modules\Booking\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Modules\Booking\Http\Requests\Client\ListUserBookingsRequest;
use Modules\Booking\Http\Resources\Client\BookingResource;
use Modules\Booking\Handlers\Client\ListUserBookingsHandler;
use Modules\Core\Http\Actions\BaseApiAction;

final class ListUserBookingsAction extends BaseApiAction
{
    public function __construct(
        private readonly ListUserBookingsHandler $handler,
    ) {}

    public function __invoke(ListUserBookingsRequest $request): JsonResponse
    {
        $bookings = $this->handler->handle($request->toQuery());

        return $this->paginated(
            paginator: $bookings,
            resourceClass: BookingResource::class,
        );
    }
}
