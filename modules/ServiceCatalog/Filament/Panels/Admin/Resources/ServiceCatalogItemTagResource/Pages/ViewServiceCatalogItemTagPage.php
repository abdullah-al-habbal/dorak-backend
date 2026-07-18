<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Filament\Panels\Admin\Resources\ServiceCatalogItemTagResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\ServiceCatalog\Filament\Panels\Admin\Resources\ServiceCatalogItemTagResource\ServiceCatalogItemTagResource;

class ViewServiceCatalogItemTagPage extends ViewRecord
{
    protected static string $resource = ServiceCatalogItemTagResource::class;
}
