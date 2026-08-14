<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Chair\Http\Actions\Client\UpdateChairAction;
use Modules\Chair\Http\Actions\Shared\ListChairsAction;
use Modules\Chair\Http\Actions\Shared\ShowChairAction;

Route::get('/chairs', ListChairsAction::class)->name('chairs.list');
Route::get('/chairs/{chair}', ShowChairAction::class)->name('chairs.show');
Route::get('/branches/{branch}/chairs', ListChairsAction::class)->name('branches.chairs.list');

Route::middleware('auth:client')->group(function (): void {
    Route::patch('/chairs/{chair}', UpdateChairAction::class)->name('chairs.update');
});
