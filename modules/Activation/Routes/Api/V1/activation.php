<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Activation\Http\Actions\Business\ActivateAction;
use Modules\Activation\Http\Actions\Business\DeactivateAction;

Route::post('/barbers/{barber}/activate', ActivateAction::class)->name('activation.activate');
Route::post('/barbers/{barber}/deactivate', DeactivateAction::class)->name('activation.deactivate');
