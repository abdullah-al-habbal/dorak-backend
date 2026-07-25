<?php

declare(strict_types=1);

namespace Modules\OfferedService\Filament\Panels\Branch\Resources\OfferedServiceResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\OfferedService\Filament\Panels\Branch\Resources\OfferedServiceResource\OfferedServiceResource;

class CreateOfferedServicePage extends CreateRecord
{
    protected static string $resource = OfferedServiceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $branchId = filament()->auth()->id();
        $data['serviceable_id'] = $branchId;
        $data['serviceable_type'] = 'branch';

        return $data;
    }
}
