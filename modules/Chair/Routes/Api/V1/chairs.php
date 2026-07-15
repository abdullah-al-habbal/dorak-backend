<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Chair\Http\Actions\ListChairsAction;
use Modules\Chair\Http\Actions\ShowChairAction;
use Modules\Chair\Http\Actions\UpdateChairAction;

Route::get('/chairs', ListChairsAction::class)->name('chairs.list');
Route::get('/chairs/{chair}', ShowChairAction::class)->name('chairs.show');
Route::get('/branches/{branch}/chairs', ListChairsAction::class)->name('branches.chairs.list');

Route::middleware('auth:client')->group(function (): void {
    Route::patch('/chairs/{chair}', UpdateChairAction::class)->name('chairs.update');
});
