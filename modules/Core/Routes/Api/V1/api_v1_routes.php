<?php

// /home/lenovo/work/me/dorak/dorak-backend/modules/Core/Routes/Api/V1/api_v1_routes.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Actions\HealthCheck\HealthCheckAction;

Route::prefix('v1')->name('api.v1.')->group(function (): void {
    Route::get('/health', HealthCheckAction::class)->name('health');
});
