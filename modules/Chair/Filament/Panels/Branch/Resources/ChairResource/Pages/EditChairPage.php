<?php

declare(strict_types=1);

namespace Modules\Chair\Filament\Panels\Branch\Resources\ChairResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Chair\Filament\Panels\Branch\Resources\ChairResource\ChairResource;

class EditChairPage extends EditRecord
{
    protected static string $resource = ChairResource::class;
}
