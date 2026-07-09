<?php
declare(strict_types=1);

namespace Modules\Currency\Filament\Panels\Admin\Resources\CurrencyResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Currency\Filament\Panels\Admin\Resources\CurrencyResource\CurrencyResource;

class ListCurrenciesPage extends ListRecords
{
    protected static string $resource = CurrencyResource::class;
}
