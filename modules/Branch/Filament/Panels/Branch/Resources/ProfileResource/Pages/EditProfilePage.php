<?php
declare(strict_types=1);

namespace Modules\Branch\Filament\Panels\Branch\Resources\ProfileResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Branch\Filament\Panels\Branch\Resources\ProfileResource\ProfileResource;

class EditProfilePage extends EditRecord
{
    protected static string $resource = ProfileResource::class;
}
