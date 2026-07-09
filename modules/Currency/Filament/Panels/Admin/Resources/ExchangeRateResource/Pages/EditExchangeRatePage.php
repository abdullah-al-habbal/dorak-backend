<?php
declare(strict_types=1);

namespace Modules\Currency\Filament\Panels\Admin\Resources\ExchangeRateResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Currency\Filament\Panels\Admin\Resources\ExchangeRateResource\ExchangeRateResource;

class EditExchangeRatePage extends EditRecord
{
    protected static string $resource = ExchangeRateResource::class;
}
