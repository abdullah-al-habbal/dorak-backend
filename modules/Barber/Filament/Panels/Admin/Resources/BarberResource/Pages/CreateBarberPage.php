<?php

declare(strict_types=1);

namespace Modules\Barber\Filament\Panels\Admin\Resources\BarberResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Barber\Filament\Panels\Admin\Resources\BarberResource\BarberResource;

class CreateBarberPage extends CreateRecord
{
    protected static string $resource = BarberResource::class;
}
