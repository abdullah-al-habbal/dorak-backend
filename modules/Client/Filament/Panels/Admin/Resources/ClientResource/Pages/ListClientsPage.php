<?php

declare(strict_types=1);

namespace Modules\Client\Filament\Panels\Admin\Resources\ClientResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Client\Filament\Panels\Admin\Resources\ClientResource\ClientResource;

class ListClientsPage extends ListRecords
{
    protected static string $resource = ClientResource::class;
}
