<?php
declare(strict_types=1);

namespace Modules\Chair\Filament\Panels\Admin\Resources\ChairResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Chair\Filament\Panels\Admin\Resources\ChairResource\ChairResource;

class EditChairPage extends EditRecord
{
    protected static string $resource = ChairResource::class;
}
