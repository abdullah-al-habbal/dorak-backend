<?php

declare(strict_types=1);

namespace Modules\Client\Repositories;

use Illuminate\Support\Facades\DB;
use Modules\Booking\Enums\BookingStatus;
use Modules\Client\Models\ClientModel;

final class DeleteAccountEloquentResolver
{
    public function execute(ClientModel $client): bool
    {
        $hasActiveBookings = $client->bookings()
            ->whereIn('status', [BookingStatus::Confirmed])
            ->exists();

        if ($hasActiveBookings) {
            return false;
        }

        DB::transaction(function () use ($client) {
            $client->tokens()->delete();
            $client->delete();
        });

        return true;
    }
}
