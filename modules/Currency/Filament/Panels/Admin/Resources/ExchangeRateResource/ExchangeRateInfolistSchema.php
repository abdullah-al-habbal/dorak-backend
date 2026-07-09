<?php
declare(strict_types=1);

namespace Modules\Currency\Filament\Panels\Admin\Resources\ExchangeRateResource;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

final class ExchangeRateInfolistSchema
{
    public static function make(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make()
                ->columns(2)
                ->schema([
                    TextEntry::make('fromCurrency.code')
                        ->label('From Currency'),
                    TextEntry::make('toCurrency.code')
                        ->label('To Currency'),
                    TextEntry::make('rate'),
                    TextEntry::make('effective_at')
                        ->dateTime(),
                ]),
        ]);
    }
}
