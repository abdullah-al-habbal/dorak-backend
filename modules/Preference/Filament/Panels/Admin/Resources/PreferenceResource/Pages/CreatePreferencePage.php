<?php
declare(strict_types=1);

namespace Modules\Preference\Filament\Panels\Admin\Resources\PreferenceResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Preference\Filament\Panels\Admin\Resources\PreferenceResource\PreferenceResource;

class CreatePreferencePage extends CreateRecord
{
    protected static string $resource = PreferenceResource::class;
}
