<?php

declare(strict_types=1);

namespace Modules\Booking\Filament\Panels\Admin\Resources\BookingResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Booking\Filament\Panels\Admin\Resources\BookingResource\BookingResource;

class CreateBookingPage extends CreateRecord
{
    protected static string $resource = BookingResource::class;
}
