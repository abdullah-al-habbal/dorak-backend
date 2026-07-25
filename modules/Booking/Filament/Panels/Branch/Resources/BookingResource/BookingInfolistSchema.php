<?php

declare(strict_types=1);

namespace Modules\Booking\Filament\Panels\Branch\Resources\BookingResource;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

final class BookingInfolistSchema
{
    public static function make(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Booking')
                ->columns(2)
                ->schema([
                    TextEntry::make('client.name.en')
                        ->label('Client'),
                    TextEntry::make('barber.name.en')
                        ->label('Barber'),
                    TextEntry::make('chair.label')
                        ->label('Chair'),
                    TextEntry::make('time_slot')
                        ->dateTime(),
                    TextEntry::make('status')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'confirmed' => 'success',
                            'completed' => 'info',
                            'canceled' => 'danger',
                            default => 'gray',
                        }),
                ]),
        ]);
    }
}
