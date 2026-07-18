<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Filament\Panels\Admin\Resources\ServiceCatalogCategoryResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\ServiceCatalog\Filament\Panels\Admin\Resources\ServiceCatalogCategoryResource\ServiceCatalogCategoryResource;

class EditServiceCatalogCategoryPage extends EditRecord
{
    protected static string $resource = ServiceCatalogCategoryResource::class;
}
