<?php
declare(strict_types=1);

namespace Modules\Language\Filament\Panels\Admin\Resources\LanguageResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Language\Filament\Panels\Admin\Resources\LanguageResource\LanguageResource;

class CreateLanguagePage extends CreateRecord
{
    protected static string $resource = LanguageResource::class;
}
