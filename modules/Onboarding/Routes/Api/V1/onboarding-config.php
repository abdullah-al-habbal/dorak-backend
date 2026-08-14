<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Onboarding\Http\Actions\Shared\GetOnboardingConfigAction;

Route::get('/app/onboarding-config', GetOnboardingConfigAction::class)->name('app.onboarding-config');
