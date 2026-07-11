<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Marketing\Http\Actions\GetFloorPlanDemoAction;
use Modules\Marketing\Http\Actions\GetMarketingPageAction;

Route::get('/marketing/pages/{slug}', GetMarketingPageAction::class)->name('marketing.pages.show');
Route::get('/website/floor-plan-demo', GetFloorPlanDemoAction::class)->name('website.floor-plan-demo');
