<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\ClientFaceProfile\Http\Actions\Client\GetFaceBasedRecommendationsAction;
use Modules\ClientFaceProfile\Http\Actions\Client\UploadFaceProfilePhotoAction;

Route::middleware('auth:client')->group(function () {
    Route::post('/client/face-profile', UploadFaceProfilePhotoAction::class)->name('client.face-profile.upload');
    Route::get('/client/face-profile/recommendations', GetFaceBasedRecommendationsAction::class)->name('client.face-profile.recommendations');
});
