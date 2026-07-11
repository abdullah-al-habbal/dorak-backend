<?php

declare(strict_types=1);

namespace Modules\Booking\Http\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Booking\Http\Resources\BookingResource;
use Modules\Booking\Models\BookingModel;
use Modules\Core\Http\Actions\BaseApiAction;

final class ListUserBookingsAction extends BaseApiAction
{
    public function __invoke(Request $request): JsonResponse
    {
        $bookings = BookingModel::where('client_id', $request->user()->id)
            ->with(['chair.barber', 'barber', 'services'])
            ->orderBy('time_slot', 'desc')
            ->paginate(20);

        return $this->paginated(
            paginator: $bookings,
            resourceClass: BookingResource::class,
        );
    }
}
