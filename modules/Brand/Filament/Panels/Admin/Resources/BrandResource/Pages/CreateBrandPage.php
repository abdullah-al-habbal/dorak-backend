<?php
declare(strict_types=1);

namespace Modules\Brand\Filament\Panels\Admin\Resources\BrandResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Brand\Filament\Panels\Admin\Resources\BrandResource\BrandResource;

class CreateBrandPage extends CreateRecord
{
    protected static string $resource = BrandResource::class;
}
