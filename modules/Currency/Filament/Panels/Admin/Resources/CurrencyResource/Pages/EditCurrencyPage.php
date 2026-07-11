<?php

declare(strict_types=1);

namespace Modules\Currency\Filament\Panels\Admin\Resources\CurrencyResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Currency\Filament\Panels\Admin\Resources\CurrencyResource\CurrencyResource;

class EditCurrencyPage extends EditRecord
{
    protected static string $resource = CurrencyResource::class;
}
