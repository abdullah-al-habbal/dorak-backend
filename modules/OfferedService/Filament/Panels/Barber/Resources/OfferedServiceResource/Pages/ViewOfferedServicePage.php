<?php

declare(strict_types=1);

namespace Modules\OfferedService\Filament\Panels\Barber\Resources\OfferedServiceResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\OfferedService\Filament\Panels\Barber\Resources\OfferedServiceResource\OfferedServiceResource;

class ViewOfferedServicePage extends ViewRecord
{
    protected static string $resource = OfferedServiceResource::class;
}
