<?php

declare(strict_types=1);

namespace Modules\Currency\Filament\Panels\Admin\Resources\CurrencyResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Currency\Filament\Panels\Admin\Resources\CurrencyResource\CurrencyResource;

class CreateCurrencyPage extends CreateRecord
{
    protected static string $resource = CurrencyResource::class;
}
