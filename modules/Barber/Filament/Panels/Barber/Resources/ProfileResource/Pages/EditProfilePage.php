<?php

declare(strict_types=1);

namespace Modules\Barber\Filament\Panels\Barber\Resources\ProfileResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Barber\Filament\Panels\Barber\Resources\ProfileResource\ProfileResource;

class EditProfilePage extends EditRecord
{
    protected static string $resource = ProfileResource::class;
}
