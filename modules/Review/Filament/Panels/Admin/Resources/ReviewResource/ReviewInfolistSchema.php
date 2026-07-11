<?php

declare(strict_types=1);

namespace Modules\Review\Filament\Panels\Admin\Resources\ReviewResource;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

final class ReviewInfolistSchema
{
    public static function make(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make()
                ->columns(2)
                ->schema([
                    TextEntry::make('booking_id')
                        ->label('Booking'),
                    TextEntry::make('author.email')
                        ->label('Author'),
                    TextEntry::make('author_type')
                        ->label('Author Type'),
                    TextEntry::make('subject_type')
                        ->label('Subject Type'),
                    TextEntry::make('rating')
                        ->badge()
                        ->color(fn (int $state): string => match (true) {
                            $state >= 4 => 'success',
                            $state >= 3 => 'warning',
                            default => 'danger',
                        }),
                    TextEntry::make('comment')
                        ->columnSpanFull(),
                    TextEntry::make('created_at')
                        ->dateTime(),
                ]),
        ]);
    }
}
