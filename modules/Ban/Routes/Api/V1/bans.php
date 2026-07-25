<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Ban\Http\Actions\Client\CheckClientBansAction;

Route::middleware('auth:client')->group(function (): void {
    Route::get('/clients/{client}/bans/check', CheckClientBansAction::class)->name('clients.bans.check');
});
