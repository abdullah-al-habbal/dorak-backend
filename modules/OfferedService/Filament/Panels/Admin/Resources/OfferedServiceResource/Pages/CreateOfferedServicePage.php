<?php
declare(strict_types=1);

namespace Modules\OfferedService\Filament\Panels\Admin\Resources\OfferedServiceResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\OfferedService\Filament\Panels\Admin\Resources\OfferedServiceResource\OfferedServiceResource;

class CreateOfferedServicePage extends CreateRecord
{
    protected static string $resource = OfferedServiceResource::class;
}
