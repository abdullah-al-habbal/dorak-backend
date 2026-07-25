<?php

declare(strict_types=1);

namespace Modules\OfferedService\Filament\Panels\Branch\Resources\OfferedServiceResource;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Modules\OfferedService\Models\OfferedServiceModel;

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
                        ->money(fn (OfferedServiceModel $record): string => $record->currency->code ?? 'USD'),
                    TextEntry::make('duration')
                        ->label('Duration (minutes)'),
                    TextEntry::make('at_home')
                        ->label('At Home')
                        ->boolean(),
                    TextEntry::make('active')
                        ->boolean(),
                ]),
        ]);
    }
}
