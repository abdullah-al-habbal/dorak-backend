<?php
declare(strict_types=1);

namespace Modules\Activation\Filament\Panels\Admin\Resources\ActivationLogResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\Activation\Filament\Panels\Admin\Resources\ActivationLogResource\ActivationLogResource;

class ViewActivationLogPage extends ViewRecord
{
    protected static string $resource = ActivationLogResource::class;
}
