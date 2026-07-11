<?php

declare(strict_types=1);

namespace Modules\Client\Filament\Panels\Admin\Resources\ClientResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\Client\Filament\Panels\Admin\Resources\ClientResource\ClientResource;

class ViewClientPage extends ViewRecord
{
    protected static string $resource = ClientResource::class;
}
