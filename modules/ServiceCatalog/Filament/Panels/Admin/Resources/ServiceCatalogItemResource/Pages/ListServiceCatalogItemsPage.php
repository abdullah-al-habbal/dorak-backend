<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Filament\Panels\Admin\Resources\ServiceCatalogItemResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\ServiceCatalog\Filament\Panels\Admin\Resources\ServiceCatalogItemResource\ServiceCatalogItemResource;

class ListServiceCatalogItemsPage extends ListRecords
{
    protected static string $resource = ServiceCatalogItemResource::class;
}
