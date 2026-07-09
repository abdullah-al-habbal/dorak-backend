<?php
declare(strict_types=1);

namespace Modules\Barber\Filament\Panels\Admin\Resources\BarberResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Barber\Filament\Panels\Admin\Resources\BarberResource\BarberResource;

class ListBarbersPage extends ListRecords
{
    protected static string $resource = BarberResource::class;
}
