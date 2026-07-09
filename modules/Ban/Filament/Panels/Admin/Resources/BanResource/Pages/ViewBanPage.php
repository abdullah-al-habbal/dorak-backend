<?php
declare(strict_types=1);

namespace Modules\Ban\Filament\Panels\Admin\Resources\BanResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\Ban\Filament\Panels\Admin\Resources\BanResource\BanResource;

class ViewBanPage extends ViewRecord
{
    protected static string $resource = BanResource::class;
}
