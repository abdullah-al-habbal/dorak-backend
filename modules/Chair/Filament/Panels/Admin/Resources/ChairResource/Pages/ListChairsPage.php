<?php
declare(strict_types=1);

namespace Modules\Chair\Filament\Panels\Admin\Resources\ChairResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Chair\Filament\Panels\Admin\Resources\ChairResource\ChairResource;

class ListChairsPage extends ListRecords
{
    protected static string $resource = ChairResource::class;
}
