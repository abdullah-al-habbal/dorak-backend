<?php
declare(strict_types=1);

namespace Modules\Barber\Filament\Panels\Barber\Resources\ProfileResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\Barber\Filament\Panels\Barber\Resources\ProfileResource\ProfileResource;

class ViewProfilePage extends ViewRecord
{
    protected static string $resource = ProfileResource::class;
}
