<?php
declare(strict_types=1);

namespace Modules\Brand\Filament\Panels\Admin\Resources\BrandResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Brand\Filament\Panels\Admin\Resources\BrandResource\BrandResource;

class ListBrandsPage extends ListRecords
{
    protected static string $resource = BrandResource::class;
}
