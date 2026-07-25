<?php

declare(strict_types=1);

namespace Modules\Chair\Filament\Panels\Branch\Resources\ChairResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Chair\Filament\Panels\Branch\Resources\ChairResource\ChairResource;

class CreateChairPage extends CreateRecord
{
    protected static string $resource = ChairResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['branch_id'] = filament()->auth()->id();

        return $data;
    }
}
