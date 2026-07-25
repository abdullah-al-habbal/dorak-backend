<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Activation\Http\Actions\Barber\ActivateBarberAction;
use Modules\Activation\Http\Actions\Barber\DeactivateBarberAction;

Route::middleware('auth:barber')->group(function (): void {
    Route::post('/barbers/{barber}/activate', ActivateBarberAction::class)->name('activation.barber.activate');
    Route::post('/barbers/{barber}/deactivate', DeactivateBarberAction::class)->name('activation.barber.deactivate');
});
