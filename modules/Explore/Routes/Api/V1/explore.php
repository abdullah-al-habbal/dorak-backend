<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Explore\Http\Actions\ExploreBranchesAction;
use Modules\Explore\Http\Actions\ExploreBarbersAction;

Route::prefix('explore')->name('explore.')->group(function (): void {
    Route::get('/branches', ExploreBranchesAction::class)->name('branches');
    Route::get('/barbers', ExploreBarbersAction::class)->name('barbers');
});
