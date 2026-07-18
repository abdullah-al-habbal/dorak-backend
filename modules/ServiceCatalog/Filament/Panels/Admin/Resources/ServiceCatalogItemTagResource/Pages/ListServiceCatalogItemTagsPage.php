<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Filament\Panels\Admin\Resources\ServiceCatalogItemTagResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\ServiceCatalog\Filament\Panels\Admin\Resources\ServiceCatalogItemTagResource\ServiceCatalogItemTagResource;

class ListServiceCatalogItemTagsPage extends ListRecords
{
    protected static string $resource = ServiceCatalogItemTagResource::class;
}
