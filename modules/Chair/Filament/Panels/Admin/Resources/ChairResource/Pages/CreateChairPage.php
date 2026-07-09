<?php
declare(strict_types=1);

namespace Modules\Chair\Filament\Panels\Admin\Resources\ChairResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Chair\Filament\Panels\Admin\Resources\ChairResource\ChairResource;

class CreateChairPage extends CreateRecord
{
    protected static string $resource = ChairResource::class;
}
