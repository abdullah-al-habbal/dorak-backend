<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\Filament\Panels\Branch\Resources\BarberAffiliationResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\BarberAffiliation\Filament\Panels\Branch\Resources\BarberAffiliationResource\BarberAffiliationResource;

class ListBarberAffiliationsPage extends ListRecords
{
    protected static string $resource = BarberAffiliationResource::class;
}
