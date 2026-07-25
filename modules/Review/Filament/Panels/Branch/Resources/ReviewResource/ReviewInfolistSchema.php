<?php

declare(strict_types=1);

namespace Modules\Review\Filament\Panels\Branch\Resources\ReviewResource;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

final class ReviewInfolistSchema
{
    public static function make(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Review')
                ->columns(2)
                ->schema([
                    TextEntry::make('author.name.en')
                        ->label('Client'),
                    TextEntry::make('booking.id')
                        ->label('Booking Reference'),
                    TextEntry::make('rating')
                        ->label('Rating')
                        ->icon('heroicon-m-star')
                        ->iconColor('warning'),
                    TextEntry::make('created_at')
                        ->dateTime()
                        ->label('Date'),
                    TextEntry::make('comment')
                        ->label('Comment')
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
