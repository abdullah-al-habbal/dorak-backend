<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Branch\Http\Actions\Branch\AcceptAffiliationAction;
use Modules\Branch\Http\Actions\Branch\CreateJobPostingAction;
use Modules\Branch\Http\Actions\Branch\DashboardAction;
use Modules\Branch\Http\Actions\Branch\DeleteJobPostingAction;
use Modules\Branch\Http\Actions\Branch\GetProfileAction;
use Modules\Branch\Http\Actions\Branch\ListAffiliationsAction;
use Modules\Branch\Http\Actions\Branch\ListApplicationsAction;
use Modules\Branch\Http\Actions\Branch\ListBookingsAction;
use Modules\Branch\Http\Actions\Branch\ListJobPostingsAction;
use Modules\Branch\Http\Actions\Branch\ListReviewsAction;
use Modules\Branch\Http\Actions\Branch\LoginAction;
use Modules\Branch\Http\Actions\Branch\RejectAffiliationAction;
use Modules\Branch\Http\Actions\Branch\ToggleChairStatusAction;
use Modules\Branch\Http\Actions\Branch\UpdateJobPostingAction;
use Modules\Branch\Http\Actions\Branch\UpdateProfileAction;

Route::prefix('branch')->name('branch.')->group(function (): void {
    Route::post('/login', LoginAction::class)->name('login');

    Route::middleware('auth:branch_api')->group(function (): void {
        Route::get('/dashboard', DashboardAction::class)->name('dashboard');
        Route::get('/profile', GetProfileAction::class)->name('profile.get');
        Route::patch('/profile', UpdateProfileAction::class)->name('profile.update');
        Route::patch('/chairs/{chair}/status', ToggleChairStatusAction::class)->name('chairs.status');
        Route::get('/affiliations', ListAffiliationsAction::class)->name('affiliations.list');
        Route::post('/affiliations/{affiliation}/accept', AcceptAffiliationAction::class)->name('affiliations.accept');
        Route::post('/affiliations/{affiliation}/reject', RejectAffiliationAction::class)->name('affiliations.reject');
        Route::get('/bookings', ListBookingsAction::class)->name('bookings.list');
        Route::get('/job-postings', ListJobPostingsAction::class)->name('job-postings.list');
        Route::post('/job-postings', CreateJobPostingAction::class)->name('job-postings.create');
        Route::put('/job-postings/{jobPosting}', UpdateJobPostingAction::class)->name('job-postings.update');
        Route::delete('/job-postings/{jobPosting}', DeleteJobPostingAction::class)->name('job-postings.delete');
        Route::get('/job-postings/{jobPosting}/applications', ListApplicationsAction::class)->name('job-postings.applications');
        Route::get('/reviews', ListReviewsAction::class)->name('reviews.list');
    });
});
