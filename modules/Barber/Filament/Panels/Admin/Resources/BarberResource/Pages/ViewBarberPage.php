<?php
declare(strict_types=1);

namespace Modules\Barber\Filament\Panels\Admin\Resources\BarberResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\Barber\Filament\Panels\Admin\Resources\BarberResource\BarberResource;

class ViewBarberPage extends ViewRecord
{
    protected static string $resource = BarberResource::class;
}
