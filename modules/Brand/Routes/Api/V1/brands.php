<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Brand\Http\Actions\CreateBrandAction;
use Modules\Brand\Http\Actions\ListBrandsAction;
use Modules\Brand\Http\Actions\ShowBrandAction;
use Modules\Brand\Http\Actions\UpdateBrandAction;

Route::get('/brands', ListBrandsAction::class)->name('brands.list');
Route::get('/brands/{brand}', ShowBrandAction::class)->name('brands.show');

Route::middleware('auth:client')->group(function (): void {
    Route::post('/brands', CreateBrandAction::class)->name('brands.create');
    Route::put('/brands/{brand}', UpdateBrandAction::class)->name('brands.update');
});
