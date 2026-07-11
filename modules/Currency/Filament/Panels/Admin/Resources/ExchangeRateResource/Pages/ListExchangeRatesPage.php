<?php

declare(strict_types=1);

namespace Modules\Currency\Filament\Panels\Admin\Resources\ExchangeRateResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Currency\Filament\Panels\Admin\Resources\ExchangeRateResource\ExchangeRateResource;

class ListExchangeRatesPage extends ListRecords
{
    protected static string $resource = ExchangeRateResource::class;
}
