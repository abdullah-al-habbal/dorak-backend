<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Filament\Panels\Admin\Resources\ServiceCatalogCategoryResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\ServiceCatalog\Filament\Panels\Admin\Resources\ServiceCatalogCategoryResource\ServiceCatalogCategoryResource;

class ViewServiceCatalogCategoryPage extends ViewRecord
{
    protected static string $resource = ServiceCatalogCategoryResource::class;
}
