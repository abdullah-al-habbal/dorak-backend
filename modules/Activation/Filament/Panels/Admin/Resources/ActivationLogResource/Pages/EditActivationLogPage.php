<?php

declare(strict_types=1);

namespace Modules\Activation\Filament\Panels\Admin\Resources\ActivationLogResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Activation\Filament\Panels\Admin\Resources\ActivationLogResource\ActivationLogResource;

class EditActivationLogPage extends EditRecord
{
    protected static string $resource = ActivationLogResource::class;
}
