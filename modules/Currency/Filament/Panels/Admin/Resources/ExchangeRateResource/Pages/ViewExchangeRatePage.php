<?php
declare(strict_types=1);

namespace Modules\Currency\Filament\Panels\Admin\Resources\ExchangeRateResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\Currency\Filament\Panels\Admin\Resources\ExchangeRateResource\ExchangeRateResource;

class ViewExchangeRatePage extends ViewRecord
{
    protected static string $resource = ExchangeRateResource::class;
}
