<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Explore\Http\Actions\ExploreBarbersAction;
use Modules\Explore\Http\Actions\ExploreBranchesAction;
use Modules\Explore\Http\Actions\GetBarberDetailAction;
use Modules\Explore\Http\Actions\GetBranchDetailAction;

Route::prefix('explore')->name('explore.')->group(function (): void {
    Route::get('/branches', ExploreBranchesAction::class)->name('branches');
    Route::get('/branches/{branch}', GetBranchDetailAction::class)->name('branches.show');
    Route::get('/barbers', ExploreBarbersAction::class)->name('barbers');
    Route::get('/barbers/{barber}', GetBarberDetailAction::class)->name('barbers.show');
});
