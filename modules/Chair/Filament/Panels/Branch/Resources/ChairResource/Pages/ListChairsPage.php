<?php

declare(strict_types=1);

namespace Modules\Chair\Filament\Panels\Branch\Resources\ChairResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Chair\Filament\Panels\Branch\Resources\ChairResource\ChairResource;

class ListChairsPage extends ListRecords
{
    protected static string $resource = ChairResource::class;
}
