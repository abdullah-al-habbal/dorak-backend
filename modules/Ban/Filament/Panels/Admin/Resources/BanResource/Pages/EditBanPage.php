<?php

declare(strict_types=1);

namespace Modules\Ban\Filament\Panels\Admin\Resources\BanResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Ban\Filament\Panels\Admin\Resources\BanResource\BanResource;

class EditBanPage extends EditRecord
{
    protected static string $resource = BanResource::class;
}
