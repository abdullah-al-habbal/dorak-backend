<?php

declare(strict_types=1);

namespace Modules\Booking\Filament\Panels\Admin\Resources\BookingResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\Booking\Filament\Panels\Admin\Resources\BookingResource\BookingResource;

class ViewBookingPage extends ViewRecord
{
    protected static string $resource = BookingResource::class;
}
