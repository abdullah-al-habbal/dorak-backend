<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\ClientInteraction\Http\Actions\Client\AddFavoriteAction;
use Modules\ClientInteraction\Http\Actions\Client\CreateSavedFilterAction;
use Modules\ClientInteraction\Http\Actions\Client\DeleteSavedFilterAction;
use Modules\ClientInteraction\Http\Actions\Client\GetDiscoveryPreferenceAction;
use Modules\ClientInteraction\Http\Actions\Client\ListFavoritesAction;
use Modules\ClientInteraction\Http\Actions\Client\ListSavedFiltersAction;
use Modules\ClientInteraction\Http\Actions\Client\RemoveFavoriteAction;
use Modules\ClientInteraction\Http\Actions\Client\ShowSavedFilterAction;
use Modules\ClientInteraction\Http\Actions\Client\UpdateDiscoveryPreferenceAction;
use Modules\ClientInteraction\Http\Actions\Client\UpdateSavedFilterAction;

Route::middleware('auth:client')->group(function () {
    Route::get('/client/favorites', ListFavoritesAction::class)->name('client.favorites.index');
    Route::post('/client/favorites', AddFavoriteAction::class)->name('client.favorites.store');
    Route::delete('/client/favorites/{favorite}', RemoveFavoriteAction::class)->name('client.favorites.destroy');

    Route::get('/client/saved-filters', ListSavedFiltersAction::class)->name('client.saved-filters.index');
    Route::post('/client/saved-filters', CreateSavedFilterAction::class)->name('client.saved-filters.store');
    Route::get('/client/saved-filters/{filter}', ShowSavedFilterAction::class)->name('client.saved-filters.show');
    Route::put('/client/saved-filters/{filter}', UpdateSavedFilterAction::class)->name('client.saved-filters.update');
    Route::delete('/client/saved-filters/{filter}', DeleteSavedFilterAction::class)->name('client.saved-filters.destroy');

    Route::get('/client/discovery-preferences', GetDiscoveryPreferenceAction::class)->name('client.discovery-preferences.show');
    Route::patch('/client/discovery-preferences', UpdateDiscoveryPreferenceAction::class)->name('client.discovery-preferences.update');
});
