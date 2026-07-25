<?php

declare(strict_types=1);

namespace Modules\Chair\Filament\Panels\Branch\Resources\ChairResource;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

final class ChairInfolistSchema
{
    public static function make(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Chair')
                ->columns(2)
                ->schema([
                    TextEntry::make('label'),
                    TextEntry::make('barber.name.en')
                        ->label('Barber')
                        ->placeholder('Unassigned'),
                    TextEntry::make('status')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'available' => 'success',
                            'occupied' => 'warning',
                            'maintenance' => 'danger',
                            default => 'gray',
                        }),
                    TextEntry::make('updated_at')
                        ->dateTime(),
                ]),
        ]);
    }
}
