<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Currency\Http\Actions\ConvertCurrencyAction;
use Modules\Currency\Http\Actions\ListCurrenciesAction;
use Modules\Currency\Http\Actions\ListExchangeRatesAction;

Route::get('/currencies', ListCurrenciesAction::class)->name('currencies.list');
Route::get('/exchange-rates', ListExchangeRatesAction::class)->name('exchange-rates.list');
Route::get('/convert', ConvertCurrencyAction::class)->name('currencies.convert');
