<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\ServiceCatalog\Http\Actions\Client\CreateCatalogItemAction;
use Modules\ServiceCatalog\Http\Actions\Client\DeleteCatalogItemAction;
use Modules\ServiceCatalog\Http\Actions\Shared\ListCatalogItemsAction;
use Modules\ServiceCatalog\Http\Actions\Shared\ShowCatalogItemAction;
use Modules\ServiceCatalog\Http\Actions\Client\UpdateCatalogItemAction;

Route::get('/service-catalog/items', ListCatalogItemsAction::class)->name('service-catalog.items.list');
Route::get('/service-catalog/items/{id}', ShowCatalogItemAction::class)->name('service-catalog.items.show');

Route::middleware('auth:client')->group(function (): void {
    Route::post('/service-catalog/items', CreateCatalogItemAction::class)->name('service-catalog.items.create');
    Route::put('/service-catalog/items/{id}', UpdateCatalogItemAction::class)->name('service-catalog.items.update');
    Route::delete('/service-catalog/items/{id}', DeleteCatalogItemAction::class)->name('service-catalog.items.delete');
});
