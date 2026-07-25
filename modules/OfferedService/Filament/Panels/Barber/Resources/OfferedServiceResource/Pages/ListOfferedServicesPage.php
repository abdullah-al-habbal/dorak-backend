<?php

declare(strict_types=1);

namespace Modules\OfferedService\Filament\Panels\Barber\Resources\OfferedServiceResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Modules\OfferedService\Filament\Panels\Barber\Resources\OfferedServiceResource\OfferedServiceResource;

class ListOfferedServicesPage extends ListRecords
{
    protected static string $resource = OfferedServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
