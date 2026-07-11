<?php

// modules/Barber/Filament/Panels/Admin/Resources/BarberResource/BarberInfolistSchema.php
declare(strict_types=1);

namespace Modules\Barber\Filament\Panels\Admin\Resources\BarberResource;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

final class BarberInfolistSchema
{
    public static function make(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Barber Details')
                ->columns(2)
                ->schema([
                    TextEntry::make('name.en')
                        ->label('Name (English)'),
                    TextEntry::make('name.ar')
                        ->label('Name (Arabic)'),
                    TextEntry::make('email'),
                    TextEntry::make('status')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'enabled' => 'success',
                            'disabled' => 'danger',
                            default => 'warning',
                        }),
                    IconEntry::make('is_freelancer')
                        ->boolean(),
                    IconEntry::make('is_enabled')
                        ->label('Enabled')
                        ->boolean(),
                    TextEntry::make('created_at')
                        ->dateTime(),
                ]),
        ]);
    }
}
