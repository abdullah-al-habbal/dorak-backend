<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Filament\Panels\Admin\Resources\ServiceCatalogItemResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\ServiceCatalog\Filament\Panels\Admin\Resources\ServiceCatalogItemResource\ServiceCatalogItemResource;

class ViewServiceCatalogItemPage extends ViewRecord
{
    protected static string $resource = ServiceCatalogItemResource::class;
}
