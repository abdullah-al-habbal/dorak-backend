<?php

declare(strict_types=1);

namespace Modules\Review\Filament\Panels\Barber\Resources\ReviewResource;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

final class ReviewInfolistSchema
{
    public static function make(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Review Details')
                ->columns(2)
                ->schema([
                    TextEntry::make('author.name.en')
                        ->label('Client'),
                    TextEntry::make('rating')
                        ->badge(),
                    TextEntry::make('comment'),
                    TextEntry::make('created_at')
                        ->dateTime(),
                ]),
        ]);
    }
}
