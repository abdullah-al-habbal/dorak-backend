<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\JobPosting\Http\Actions\Barber\ApplyForJobAction;
use Modules\JobPosting\Http\Actions\Shared\ListJobPostingsAction;
use Modules\JobPosting\Http\Actions\Shared\ShowJobPostingAction;

Route::get('/jobs', ListJobPostingsAction::class)->name('jobs.list');
Route::get('/jobs/{job}', ShowJobPostingAction::class)->name('jobs.show');
Route::post('/jobs/{job}/apply', ApplyForJobAction::class)
    ->middleware('auth:barber')
    ->name('jobs.apply');
