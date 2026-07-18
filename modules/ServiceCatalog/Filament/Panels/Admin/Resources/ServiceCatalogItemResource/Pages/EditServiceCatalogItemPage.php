<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Filament\Panels\Admin\Resources\ServiceCatalogItemResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\ServiceCatalog\Filament\Panels\Admin\Resources\ServiceCatalogItemResource\ServiceCatalogItemResource;

class EditServiceCatalogItemPage extends EditRecord
{
    protected static string $resource = ServiceCatalogItemResource::class;
}
