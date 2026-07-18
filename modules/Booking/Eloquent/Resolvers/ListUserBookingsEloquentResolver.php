<?php

declare(strict_types=1);

namespace Modules\Booking\Eloquent\Resolvers;

use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Booking\CQRS\Query\ListUserBookingsQuery;
use Modules\Booking\Enums\BookingFilterStatus;
use Modules\Booking\Models\BookingModel;

final class ListUserBookingsEloquentResolver
{
    public function resolve(ListUserBookingsQuery $query): LengthAwarePaginator
    {
        $now = Carbon::now();

        $queryBuilder = BookingModel::where('client_id', $query->clientId)
            ->with(['chair.barber', 'barber', 'services']);

        if ($query->filterStatus === null) {
            return $queryBuilder->orderBy('time_slot', 'desc')->paginate($query->perPage);
        }

        match ($query->filterStatus) {
            BookingFilterStatus::Upcoming => $queryBuilder
                ->whereIn('status', ['confirmed'])
                ->where('time_slot', '>=', $now),
            BookingFilterStatus::Past => $queryBuilder
                ->where(function ($q) use ($now): void {
                    $q->where('status', 'completed')
                        ->orWhere(function ($q2) use ($now): void {
                            $q2->whereIn('status', ['confirmed', 'canceled'])
                                ->where('time_slot', '<', $now);
                        });
                }),
        };

        return $queryBuilder->orderBy('time_slot', 'desc')->paginate($query->perPage);
    }
}
