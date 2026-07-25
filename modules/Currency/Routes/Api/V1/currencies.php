<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Currency\Http\Actions\Admin\ConvertCurrencyAction;
use Modules\Currency\Http\Actions\Admin\ListExchangeRatesAction;
use Modules\Currency\Http\Actions\Shared\ListCurrenciesAction;

Route::get('/currencies', ListCurrenciesAction::class)->name('currencies.list');

Route::middleware('auth:admin')->group(function (): void {
    Route::get('/exchange-rates', ListExchangeRatesAction::class)->name('exchange-rates.list');
    Route::post('/currency/convert', ConvertCurrencyAction::class)->name('currencies.convert');
});
