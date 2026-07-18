<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Booking\Http\Actions\Client\CancelBookingAction;
use Modules\Booking\Http\Actions\Client\CreateBookingAction;
use Modules\Booking\Http\Actions\Client\ListUserBookingsAction;
use Modules\Booking\Http\Actions\Client\ShowBookingAction;

Route::middleware('auth:client')->group(function (): void {
    Route::post('/bookings', CreateBookingAction::class)->name('bookings.create');
    Route::get('/bookings', ListUserBookingsAction::class)->name('bookings.list');
    Route::get('/bookings/{booking}', ShowBookingAction::class)->name('bookings.show');
    Route::post('/client/bookings/{booking}/cancel', CancelBookingAction::class)->name('bookings.cancel');
});
