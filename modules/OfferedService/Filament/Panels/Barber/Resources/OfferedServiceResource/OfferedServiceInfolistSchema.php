<?php

declare(strict_types=1);

namespace Modules\OfferedService\Filament\Panels\Barber\Resources\OfferedServiceResource;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

final class OfferedServiceInfolistSchema
{
    public static function make(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Service Details')
                ->columns(2)
                ->schema([
                    TextEntry::make('name.en')
                        ->label('Name (English)'),
                    TextEntry::make('name.ar')
                        ->label('Name (Arabic)'),
                    TextEntry::make('price')
                        ->money(),
                    TextEntry::make('currency.code')
                        ->label('Currency'),
                    TextEntry::make('duration')
                        ->label('Duration (minutes)'),
                    TextEntry::make('at_home')
                        ->label('Available at Home')
                        ->boolean(),
                    TextEntry::make('active')
                        ->boolean(),
                    TextEntry::make('created_at')
                        ->dateTime(),
                ]),
        ]);
    }
}
