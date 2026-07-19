<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Ban\Http\Actions\Client\CheckClientBanAction;

Route::get('/clients/{client}/bans/check', CheckClientBanAction::class)->name('clients.bans.check');
