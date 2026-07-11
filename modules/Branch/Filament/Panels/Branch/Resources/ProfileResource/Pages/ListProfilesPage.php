<?php

declare(strict_types=1);

namespace Modules\Branch\Filament\Panels\Branch\Resources\ProfileResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Branch\Filament\Panels\Branch\Resources\ProfileResource\ProfileResource;

class ListProfilesPage extends ListRecords
{
    protected static string $resource = ProfileResource::class;
}
