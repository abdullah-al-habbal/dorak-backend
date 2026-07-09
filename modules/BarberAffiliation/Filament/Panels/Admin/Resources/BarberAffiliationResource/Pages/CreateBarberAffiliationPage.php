<?php
declare(strict_types=1);

namespace Modules\BarberAffiliation\Filament\Panels\Admin\Resources\BarberAffiliationResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\BarberAffiliation\Filament\Panels\Admin\Resources\BarberAffiliationResource\BarberAffiliationResource;

class CreateBarberAffiliationPage extends CreateRecord
{
    protected static string $resource = BarberAffiliationResource::class;
}
