<?php

declare(strict_types=1);

namespace Modules\OfferedService\Filament\Panels\Admin\Resources\OfferedServiceResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\OfferedService\Filament\Panels\Admin\Resources\OfferedServiceResource\OfferedServiceResource;

class EditOfferedServicePage extends EditRecord
{
    protected static string $resource = OfferedServiceResource::class;
}
