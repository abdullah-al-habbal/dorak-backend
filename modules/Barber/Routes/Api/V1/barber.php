<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Barber\Http\Actions\Barber\DeletePortfolioPhotoAction;
use Modules\Barber\Http\Actions\Barber\GetProfileAction;
use Modules\Barber\Http\Actions\Barber\GetScheduleAction;
use Modules\Barber\Http\Actions\Barber\LoginAction;
use Modules\Barber\Http\Actions\Barber\UpdateProfileAction;
use Modules\Barber\Http\Actions\Barber\UpdateScheduleAction;
use Modules\Barber\Http\Actions\Barber\UpdateTravelRadiusAction;
use Modules\Barber\Http\Actions\Barber\UploadPortfolioAction;

Route::prefix('barber')->name('barber.')->group(function (): void {
    Route::post('/login', LoginAction::class)->name('login');

    Route::middleware('auth:barber')->group(function (): void {
        Route::get('/profile', GetProfileAction::class)->name('profile.show');
        Route::patch('/profile', UpdateProfileAction::class)->name('profile.update');
        Route::post('/portfolio', UploadPortfolioAction::class)->name('portfolio.upload');
        Route::delete('/portfolio/{photo}', DeletePortfolioPhotoAction::class)->name('portfolio.delete');
        Route::patch('/travel-radius', UpdateTravelRadiusAction::class)->name('travel-radius.update');
        Route::get('/schedule', GetScheduleAction::class)->name('schedule.show');
        Route::patch('/schedule', UpdateScheduleAction::class)->name('schedule.update');
    });
});
