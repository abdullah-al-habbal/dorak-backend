<?php
declare(strict_types=1);

namespace Modules\Currency\Filament\Panels\Admin\Resources\CurrencyResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\Currency\Filament\Panels\Admin\Resources\CurrencyResource\CurrencyResource;

class ViewCurrencyPage extends ViewRecord
{
    protected static string $resource = CurrencyResource::class;
}
