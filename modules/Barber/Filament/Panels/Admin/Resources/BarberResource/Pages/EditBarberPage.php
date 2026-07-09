<?php
declare(strict_types=1);

namespace Modules\Barber\Filament\Panels\Admin\Resources\BarberResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Barber\Filament\Panels\Admin\Resources\BarberResource\BarberResource;

class EditBarberPage extends EditRecord
{
    protected static string $resource = BarberResource::class;
}
