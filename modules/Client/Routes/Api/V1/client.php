<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Client\Http\Actions\LoginAction;
use Modules\Client\Http\Actions\LogoutAction;
use Modules\Client\Http\Actions\RefreshTokenAction;
use Modules\Client\Http\Actions\RegisterAction;
use Modules\Client\Http\Actions\UpdateUniversePreferenceAction;

Route::prefix('client')->name('client.')->group(function (): void {
    Route::post('/login', LoginAction::class)->name('login');
    Route::post('/register', RegisterAction::class)->name('register');

    Route::middleware('auth:client')->group(function (): void {
        Route::post('/logout', LogoutAction::class)->name('logout');
        Route::post('/refresh-token', RefreshTokenAction::class)->name('refresh-token');
        Route::patch('/preferences/universe', UpdateUniversePreferenceAction::class)->name('preferences.universe');
    });
});
