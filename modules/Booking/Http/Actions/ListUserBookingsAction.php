<?php

declare(strict_types=1);

namespace Modules\Booking\Http\Actions;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Booking\Http\Resources\BookingResource;
use Modules\Booking\Models\BookingModel;
use Modules\Core\Http\Actions\BaseApiAction;

final class ListUserBookingsAction extends BaseApiAction
{
    public function __invoke(Request $request): JsonResponse
    {
        $status = $request->query('status');
        $now = Carbon::now();

        $query = BookingModel::where('client_id', $request->user()->id)
            ->with(['chair.barber', 'barber', 'services']);

        if ($status === 'upcoming') {
            $query->whereIn('status', ['confirmed'])
                ->where('time_slot', '>=', $now);
        } elseif ($status === 'past') {
            $query->where(function ($q) use ($now): void {
                $q->where('status', 'completed')
                    ->orWhere(function ($q2) use ($now): void {
                        $q2->whereIn('status', ['confirmed', 'canceled'])
                            ->where('time_slot', '<', $now);
                    });
            });
        }

        $bookings = $query->orderBy('time_slot', 'desc')->paginate(20);

        return $this->paginated(
            paginator: $bookings,
            resourceClass: BookingResource::class,
        );
    }
}
