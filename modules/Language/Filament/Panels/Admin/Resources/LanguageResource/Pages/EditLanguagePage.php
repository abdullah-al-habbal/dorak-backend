<?php

declare(strict_types=1);

namespace Modules\Language\Filament\Panels\Admin\Resources\LanguageResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Language\Filament\Panels\Admin\Resources\LanguageResource\LanguageResource;

class EditLanguagePage extends EditRecord
{
    protected static string $resource = LanguageResource::class;
}
