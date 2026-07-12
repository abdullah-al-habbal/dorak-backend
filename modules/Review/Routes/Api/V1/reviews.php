<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Review\Http\Actions\GetBranchReviewsAction;
use Modules\Review\Http\Actions\SubmitReviewAction;

Route::middleware('auth:client')->group(function (): void {
    Route::post('/client/bookings/{booking}/review', SubmitReviewAction::class)->name('reviews.submit');
});

Route::get('/branches/{branch}/reviews', GetBranchReviewsAction::class)->name('reviews.branch');
