<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Client\Http\Actions\DeleteAccountAction;
use Modules\Client\Http\Actions\ForgotPasswordAction;
use Modules\Client\Http\Actions\LoginAction;
use Modules\Client\Http\Actions\LogoutAction;
use Modules\Client\Http\Actions\RefreshTokenAction;
use Modules\Client\Http\Actions\RegisterAction;
use Modules\Client\Http\Actions\ResetPasswordAction;
use Modules\Client\Http\Actions\SendEmailVerificationAction;
use Modules\Client\Http\Actions\SocialLoginAction;
use Modules\Client\Http\Actions\UpdateProfileAction;
use Modules\Client\Http\Actions\UpdateUniversePreferenceAction;
use Modules\Client\Http\Actions\UploadAvatarAction;
use Modules\Client\Http\Actions\VerifyEmailAction;

Route::prefix('client')->name('client.')->group(function (): void {
    Route::post('/login', LoginAction::class)->name('login');
    Route::post('/register', RegisterAction::class)->name('register');
    Route::post('/social/{provider}', SocialLoginAction::class)->name('social.login');
    Route::post('/forgot-password', ForgotPasswordAction::class)->name('forgot-password');
    Route::post('/reset-password', ResetPasswordAction::class)->name('reset-password');

    Route::middleware('auth:client')->group(function (): void {
        Route::post('/logout', LogoutAction::class)->name('logout');
        Route::post('/refresh-token', RefreshTokenAction::class)->name('refresh-token');
        Route::patch('/preferences/universe', UpdateUniversePreferenceAction::class)->name('preferences.universe');
        Route::patch('/profile', UpdateProfileAction::class)->name('profile.update');
        Route::post('/avatar', UploadAvatarAction::class)->name('avatar.upload');
        Route::delete('/account', DeleteAccountAction::class)->name('account.delete');
        Route::post('/email/verify', VerifyEmailAction::class)->name('email.verify');
        Route::post('/email/verify/send', SendEmailVerificationAction::class)->name('email.verify.send');
    });
});
