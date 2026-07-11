<?php

declare(strict_types=1);

namespace Modules\Language\Filament\Panels\Admin\Resources\LanguageResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Language\Filament\Panels\Admin\Resources\LanguageResource\LanguageResource;

class ListLanguagesPage extends ListRecords
{
    protected static string $resource = LanguageResource::class;
}
