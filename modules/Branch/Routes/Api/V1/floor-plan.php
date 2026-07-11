<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Branch\Http\Actions\GetFloorPlanAction;

Route::get('/branches/{branch}/floor-plan', GetFloorPlanAction::class)->name('branches.floor-plan');
