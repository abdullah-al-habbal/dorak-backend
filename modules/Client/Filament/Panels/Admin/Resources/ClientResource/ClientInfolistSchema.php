<?php

declare(strict_types=1);

namespace Modules\Client\Filament\Panels\Admin\Resources\ClientResource;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

final class ClientInfolistSchema
{
    public static function make(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Client Details')
                ->columns(2)
                ->schema([
                    TextEntry::make('name.en')
                        ->label('Name (English)'),
                    TextEntry::make('name.ar')
                        ->label('Name (Arabic)'),
                    TextEntry::make('email'),
                    TextEntry::make('phone'),
                    TextEntry::make('status')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'enabled' => 'success',
                            'disabled' => 'danger',
                            default => 'warning',
                        }),
                    TextEntry::make('created_at')
                        ->dateTime(),
                ]),
        ]);
    }
}
