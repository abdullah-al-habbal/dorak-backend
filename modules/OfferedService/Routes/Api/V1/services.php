<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\OfferedService\Http\Actions\Shared\ListBarberServicesAction;

Route::get('/barbers/{barber}/services', ListBarberServicesAction::class)->name('barbers.services');
