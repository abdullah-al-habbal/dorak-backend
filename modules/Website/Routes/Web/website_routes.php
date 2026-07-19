<?php

// dorak-backend/modules/Website/Routes/Web/website_routes.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Website\Http\Actions\Shared\ShowFeaturesPageAction;
use Modules\Website\Http\Actions\Shared\ShowHomePageAction;
use Modules\Website\Http\Actions\Shared\ShowPricingPageAction;
use Modules\Website\Http\Middleware\SetLocaleMiddleware;

Route::withoutMiddleware([SetLocaleMiddleware::class])
    ->get('/', fn () => redirect()->to(app()->getLocale()));

Route::prefix('/{locale}')
    ->middleware(SetLocaleMiddleware::class)
    ->whereIn('locale', ['ar', 'en'])
    ->group(function (): void {
        Route::get('/', ShowHomePageAction::class)->name('website.home');
        Route::get('/features', ShowFeaturesPageAction::class)->name('website.features');
        Route::get('/pricing', ShowPricingPageAction::class)->name('website.pricing');
    });
