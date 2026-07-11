<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Booking\Http\Actions\CreateBookingAction;
use Modules\Booking\Http\Actions\ListUserBookingsAction;

Route::middleware('auth:client')->group(function (): void {
    Route::post('/bookings', CreateBookingAction::class)->name('bookings.create');
    Route::get('/bookings', ListUserBookingsAction::class)->name('bookings.list');
});
