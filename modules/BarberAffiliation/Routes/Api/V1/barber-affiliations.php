<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\BarberAffiliation\Http\Actions\AcceptAffiliationAction;
use Modules\BarberAffiliation\Http\Actions\CreateAffiliationAction;
use Modules\BarberAffiliation\Http\Actions\ListBarberAffiliationsAction;
use Modules\BarberAffiliation\Http\Actions\RejectAffiliationAction;

Route::middleware('auth:barber')->group(function (): void {
    Route::post('/barbers/{barber}/affiliate', CreateAffiliationAction::class)->name('affiliations.create');
    Route::post('/affiliations/{affiliation}/accept', AcceptAffiliationAction::class)->name('affiliations.accept');
    Route::post('/affiliations/{affiliation}/reject', RejectAffiliationAction::class)->name('affiliations.reject');
    Route::get('/barbers/{barber}/affiliations', ListBarberAffiliationsAction::class)->name('affiliations.list');
});
