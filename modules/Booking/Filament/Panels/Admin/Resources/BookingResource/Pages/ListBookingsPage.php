<?php

declare(strict_types=1);

namespace Modules\Booking\Filament\Panels\Admin\Resources\BookingResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Booking\Filament\Panels\Admin\Resources\BookingResource\BookingResource;

class ListBookingsPage extends ListRecords
{
    protected static string $resource = BookingResource::class;
}
