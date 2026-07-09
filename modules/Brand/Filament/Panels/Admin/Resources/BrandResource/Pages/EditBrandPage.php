<?php
declare(strict_types=1);

namespace Modules\Brand\Filament\Panels\Admin\Resources\BrandResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Brand\Filament\Panels\Admin\Resources\BrandResource\BrandResource;

class EditBrandPage extends EditRecord
{
    protected static string $resource = BrandResource::class;
}
