<?php

declare(strict_types=1);

namespace Modules\OfferedService\Filament\Panels\Admin\Resources\OfferedServiceResource;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

final class OfferedServiceInfolistSchema
{
    public static function make(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make()
                ->columns(2)
                ->schema([
                    TextEntry::make('name.en')
                        ->label('Name (English)'),
                    TextEntry::make('name.ar')
                        ->label('Name (Arabic)'),
                    TextEntry::make('serviceable_type')
                        ->label('Entity Type'),
                    TextEntry::make('currency.code')
                        ->label('Currency'),
                    TextEntry::make('price'),
                    TextEntry::make('duration')
                        ->suffix(' min'),
                    IconEntry::make('at_home')
                        ->boolean(),
                    IconEntry::make('active')
                        ->boolean(),
                    TextEntry::make('description.en')
                        ->label('Description')
                        ->html(),
                ]),
        ]);
    }
}
