<?php
declare(strict_types=1);

namespace Modules\Currency\Filament\Panels\Admin\Resources\ExchangeRateResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Currency\Filament\Panels\Admin\Resources\ExchangeRateResource\ExchangeRateResource;

class CreateExchangeRatePage extends CreateRecord
{
    protected static string $resource = ExchangeRateResource::class;
}
