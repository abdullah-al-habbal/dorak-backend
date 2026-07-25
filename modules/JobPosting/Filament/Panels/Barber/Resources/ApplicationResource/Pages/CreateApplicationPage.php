<?php

declare(strict_types=1);

namespace Modules\JobPosting\Filament\Panels\Barber\Resources\ApplicationResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\JobPosting\Filament\Panels\Barber\Resources\ApplicationResource\ApplicationResource;

class CreateApplicationPage extends CreateRecord
{
    protected static string $resource = ApplicationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['barber_id'] = filament()->auth()->id();
        $data['status'] = 'submitted';

        return $data;
    }
}
