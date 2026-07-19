<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Client\Http\Actions\Client\ChangePasswordAction;
use Modules\Client\Http\Actions\Client\DeleteAccountAction;
use Modules\Client\Http\Actions\Client\ForgotPasswordAction;
use Modules\Client\Http\Actions\Client\LoginAction;
use Modules\Client\Http\Actions\Client\LogoutAction;
use Modules\Client\Http\Actions\Client\RefreshTokenAction;
use Modules\Client\Http\Actions\Client\RegisterAction;
use Modules\Client\Http\Actions\Client\ResetPasswordAction;
use Modules\Client\Http\Actions\Client\SendEmailVerificationAction;
use Modules\Client\Http\Actions\Client\SocialLoginAction;
use Modules\Client\Http\Actions\Client\UpdateProfileAction;
use Modules\Client\Http\Actions\Client\UpdateUniversePreferenceAction;
use Modules\Client\Http\Actions\Client\UploadAvatarAction;
use Modules\Client\Http\Actions\Client\VerifyEmailAction;

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
        Route::patch('/password', ChangePasswordAction::class)->name('password.change');
    });
});
