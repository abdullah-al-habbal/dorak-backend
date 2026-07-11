<?php

declare(strict_types=1);

namespace Modules\Ban\Filament\Panels\Admin\Resources\BanResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Ban\Filament\Panels\Admin\Resources\BanResource\BanResource;

class CreateBanPage extends CreateRecord
{
    protected static string $resource = BanResource::class;
}
