<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Brand\Http\Actions\ListBrandsAction;
use Modules\Brand\Http\Actions\ShowBrandAction;

Route::get('/brands', ListBrandsAction::class)->name('brands.list');
Route::get('/brands/{brand}', ShowBrandAction::class)->name('brands.show');
