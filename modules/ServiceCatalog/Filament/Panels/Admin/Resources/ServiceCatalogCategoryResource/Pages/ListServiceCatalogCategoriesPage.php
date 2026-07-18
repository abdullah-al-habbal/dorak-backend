<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Filament\Panels\Admin\Resources\ServiceCatalogCategoryResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\ServiceCatalog\Filament\Panels\Admin\Resources\ServiceCatalogCategoryResource\ServiceCatalogCategoryResource;

class ListServiceCatalogCategoriesPage extends ListRecords
{
    protected static string $resource = ServiceCatalogCategoryResource::class;
}
