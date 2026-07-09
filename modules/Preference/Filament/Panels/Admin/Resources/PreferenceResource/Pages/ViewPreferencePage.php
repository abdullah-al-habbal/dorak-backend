<?php
declare(strict_types=1);

namespace Modules\Preference\Filament\Panels\Admin\Resources\PreferenceResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\Preference\Filament\Panels\Admin\Resources\PreferenceResource\PreferenceResource;

class ViewPreferencePage extends ViewRecord
{
    protected static string $resource = PreferenceResource::class;
}
