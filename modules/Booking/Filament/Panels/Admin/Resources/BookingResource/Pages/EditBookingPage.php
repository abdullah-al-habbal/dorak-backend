<?php
declare(strict_types=1);

namespace Modules\Booking\Filament\Panels\Admin\Resources\BookingResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Booking\Filament\Panels\Admin\Resources\BookingResource\BookingResource;

class EditBookingPage extends EditRecord
{
    protected static string $resource = BookingResource::class;
}
