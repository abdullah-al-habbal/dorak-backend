<?php

declare(strict_types=1);

namespace Modules\Language\Filament\Panels\Admin\Resources\LanguageResource;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

final class LanguageInfolistSchema
{
    public static function make(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make()
                ->columns(2)
                ->schema([
                    TextEntry::make('code'),
                    TextEntry::make('name.en')
                        ->label('Name (English)'),
                    TextEntry::make('name.ar')
                        ->label('Name (Arabic)'),
                    TextEntry::make('direction')
                        ->badge()
                        ->color(fn (string $state): string => $state === 'rtl' ? 'info' : 'gray'),
                    IconEntry::make('is_default')
                        ->boolean(),
                ]),
        ]);
    }
}
