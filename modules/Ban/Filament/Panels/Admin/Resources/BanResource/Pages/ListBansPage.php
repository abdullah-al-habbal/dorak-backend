<?php
declare(strict_types=1);

namespace Modules\Ban\Filament\Panels\Admin\Resources\BanResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Ban\Filament\Panels\Admin\Resources\BanResource\BanResource;

class ListBansPage extends ListRecords
{
    protected static string $resource = BanResource::class;
}
