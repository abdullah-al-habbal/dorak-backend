<?php

declare(strict_types=1);

namespace Modules\Booking\Filament\Panels\Admin\Resources\BookingResource;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

final class BookingInfolistSchema
{
    public static function make(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make()
                ->columns(2)
                ->schema([
                    TextEntry::make('client.email')
                        ->label('Client'),
                    TextEntry::make('chair.label')
                        ->label('Chair'),
                    TextEntry::make('barber.email')
                        ->label('Barber'),
                    TextEntry::make('time_slot')
                        ->dateTime(),
                    TextEntry::make('status')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'confirmed' => 'success',
                            'completed' => 'info',
                            'cancelled' => 'danger',
                            'in_progress' => 'warning',
                            default => 'gray',
                        }),
                    TextEntry::make('created_at')
                        ->dateTime(),
                ]),
        ]);
    }
}
