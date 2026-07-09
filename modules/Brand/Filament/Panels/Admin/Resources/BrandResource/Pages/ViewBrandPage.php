<?php
declare(strict_types=1);

namespace Modules\Brand\Filament\Panels\Admin\Resources\BrandResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\Brand\Filament\Panels\Admin\Resources\BrandResource\BrandResource;

class ViewBrandPage extends ViewRecord
{
    protected static string $resource = BrandResource::class;
}
