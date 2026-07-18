<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Filament\Panels\Admin\Resources\ServiceCatalogItemTagResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\ServiceCatalog\Filament\Panels\Admin\Resources\ServiceCatalogItemTagResource\ServiceCatalogItemTagResource;

class EditServiceCatalogItemTagPage extends EditRecord
{
    protected static string $resource = ServiceCatalogItemTagResource::class;
}
