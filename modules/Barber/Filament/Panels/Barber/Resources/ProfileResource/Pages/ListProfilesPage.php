<?php
declare(strict_types=1);

namespace Modules\Barber\Filament\Panels\Barber\Resources\ProfileResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Barber\Filament\Panels\Barber\Resources\ProfileResource\ProfileResource;

class ListProfilesPage extends ListRecords
{
    protected static string $resource = ProfileResource::class;
}
