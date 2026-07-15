<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\JobPosting\Http\Actions\ListApplicationsAction;
use Modules\JobPosting\Http\Actions\UpdateApplicationStatusAction;

Route::middleware('auth:barber')->group(function (): void {
    Route::get('/applications', ListApplicationsAction::class)->name('applications.list');
    Route::put('/applications/{application}/status', UpdateApplicationStatusAction::class)->name('applications.status');
});
