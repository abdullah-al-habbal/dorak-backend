<?php

declare(strict_types=1);

namespace Modules\JobPosting\Filament\Panels\Barber\Resources\ApplicationResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Modules\JobPosting\Filament\Panels\Barber\Resources\ApplicationResource\ApplicationResource;

class ListApplicationsPage extends ListRecords
{
    protected static string $resource = ApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
