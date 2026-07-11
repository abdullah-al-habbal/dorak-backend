<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\Filament\Panels\Admin\Resources\BarberAffiliationResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\BarberAffiliation\Filament\Panels\Admin\Resources\BarberAffiliationResource\BarberAffiliationResource;

class EditBarberAffiliationPage extends EditRecord
{
    protected static string $resource = BarberAffiliationResource::class;
}
