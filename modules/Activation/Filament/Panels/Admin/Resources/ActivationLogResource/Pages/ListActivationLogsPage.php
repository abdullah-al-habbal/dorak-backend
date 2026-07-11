<?php

declare(strict_types=1);

namespace Modules\Activation\Filament\Panels\Admin\Resources\ActivationLogResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Activation\Filament\Panels\Admin\Resources\ActivationLogResource\ActivationLogResource;

class ListActivationLogsPage extends ListRecords
{
    protected static string $resource = ActivationLogResource::class;
}
