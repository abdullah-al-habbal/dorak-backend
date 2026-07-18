<?php

declare(strict_types=1);

namespace Modules\Booking\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\Booking\Http\Requests\ListUserBookingsRequest;
use Modules\Booking\Http\Resources\BookingResource;
use Modules\Booking\Handlers\ListUserBookingsHandler;
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
