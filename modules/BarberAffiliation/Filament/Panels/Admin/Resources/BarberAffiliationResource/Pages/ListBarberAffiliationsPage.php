<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\Filament\Panels\Admin\Resources\BarberAffiliationResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\BarberAffiliation\Filament\Panels\Admin\Resources\BarberAffiliationResource\BarberAffiliationResource;

class ListBarberAffiliationsPage extends ListRecords
{
    protected static string $resource = BarberAffiliationResource::class;
}
