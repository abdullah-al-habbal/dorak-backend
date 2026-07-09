<?php
declare(strict_types=1);

namespace Modules\Preference\Filament\Panels\Admin\Resources\PreferenceResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Preference\Filament\Panels\Admin\Resources\PreferenceResource\PreferenceResource;

class ListPreferencesPage extends ListRecords
{
    protected static string $resource = PreferenceResource::class;
}
