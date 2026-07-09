<?php
declare(strict_types=1);

namespace Modules\Language\Filament\Panels\Admin\Resources\LanguageResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\Language\Filament\Panels\Admin\Resources\LanguageResource\LanguageResource;

class ViewLanguagePage extends ViewRecord
{
    protected static string $resource = LanguageResource::class;
}
