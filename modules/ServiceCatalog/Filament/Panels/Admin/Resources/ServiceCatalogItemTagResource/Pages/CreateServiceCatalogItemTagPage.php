<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Filament\Panels\Admin\Resources\ServiceCatalogItemTagResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\ServiceCatalog\Filament\Panels\Admin\Resources\ServiceCatalogItemTagResource\ServiceCatalogItemTagResource;

class CreateServiceCatalogItemTagPage extends CreateRecord
{
    protected static string $resource = ServiceCatalogItemTagResource::class;
}
