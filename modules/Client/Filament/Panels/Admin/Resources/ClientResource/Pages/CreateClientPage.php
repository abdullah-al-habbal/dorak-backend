<?php

declare(strict_types=1);

namespace Modules\Client\Filament\Panels\Admin\Resources\ClientResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Client\Filament\Panels\Admin\Resources\ClientResource\ClientResource;

class CreateClientPage extends CreateRecord
{
    protected static string $resource = ClientResource::class;
}
