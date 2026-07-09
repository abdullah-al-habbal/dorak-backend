<?php
declare(strict_types=1);

namespace Modules\OfferedService\Filament\Panels\Admin\Resources\OfferedServiceResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\OfferedService\Filament\Panels\Admin\Resources\OfferedServiceResource\OfferedServiceResource;

class ListOfferedServicesPage extends ListRecords
{
    protected static string $resource = OfferedServiceResource::class;
}
