<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Filament\Panels\Admin\Resources\ServiceCatalogCategoryResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\ServiceCatalog\Filament\Panels\Admin\Resources\ServiceCatalogCategoryResource\ServiceCatalogCategoryResource;

class CreateServiceCatalogCategoryPage extends CreateRecord
{
    protected static string $resource = ServiceCatalogCategoryResource::class;
}
