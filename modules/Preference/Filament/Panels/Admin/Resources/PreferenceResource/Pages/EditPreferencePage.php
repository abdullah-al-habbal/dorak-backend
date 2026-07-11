<?php

declare(strict_types=1);

namespace Modules\Preference\Filament\Panels\Admin\Resources\PreferenceResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Preference\Filament\Panels\Admin\Resources\PreferenceResource\PreferenceResource;

class EditPreferencePage extends EditRecord
{
    protected static string $resource = PreferenceResource::class;
}
