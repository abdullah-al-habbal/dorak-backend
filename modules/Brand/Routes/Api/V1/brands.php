<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Brand\Http\Actions\Client\CreateBrandAction;
use Modules\Brand\Http\Actions\Shared\ListBrandsAction;
use Modules\Brand\Http\Actions\Shared\ShowBrandAction;
use Modules\Brand\Http\Actions\Client\UpdateBrandAction;

Route::get('/brands', ListBrandsAction::class)->name('brands.list');
Route::get('/brands/{brand}', ShowBrandAction::class)->name('brands.show');

Route::middleware('auth:client')->group(function (): void {
    Route::post('/brands', CreateBrandAction::class)->name('brands.create');
    Route::put('/brands/{brand}', UpdateBrandAction::class)->name('brands.update');
});
