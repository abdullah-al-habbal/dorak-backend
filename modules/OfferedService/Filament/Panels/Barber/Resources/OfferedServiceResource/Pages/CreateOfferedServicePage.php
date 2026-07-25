<?php

declare(strict_types=1);

namespace Modules\OfferedService\Filament\Panels\Barber\Resources\OfferedServiceResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\OfferedService\Filament\Panels\Barber\Resources\OfferedServiceResource\OfferedServiceResource;

class CreateOfferedServicePage extends CreateRecord
{
    protected static string $resource = OfferedServiceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['serviceable_id'] = filament()->auth()->id();
        $data['serviceable_type'] = 'barber';

        return $data;
    }
}
