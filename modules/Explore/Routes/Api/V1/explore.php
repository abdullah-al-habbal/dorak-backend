<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Explore\Http\Actions\Shared\ExploreBarbersAction;
use Modules\Explore\Http\Actions\Shared\ExploreBranchesAction;
use Modules\Explore\Http\Actions\Shared\GetBarberDetailAction;
use Modules\Explore\Http\Actions\Shared\GetBranchDetailAction;

Route::prefix('explore')->name('explore.')->group(function (): void {
    Route::get('/branches', ExploreBranchesAction::class)->name('branches');
    Route::get('/branches/{branch}', GetBranchDetailAction::class)->name('branches.show');
    Route::get('/barbers', ExploreBarbersAction::class)->name('barbers');
    Route::get('/barbers/{barber}', GetBarberDetailAction::class)->name('barbers.show');
});
