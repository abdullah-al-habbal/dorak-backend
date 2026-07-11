<?php

declare(strict_types=1);

namespace Modules\Ban\Filament\Panels\Admin\Resources\BanResource;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

final class BanInfolistSchema
{
    public static function make(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make()
                ->columns(2)
                ->schema([
                    TextEntry::make('bannable_type')
                        ->label('Entity Type'),
                    TextEntry::make('bannable_id')
                        ->label('Entity ID'),
                    TextEntry::make('reason'),
                    TextEntry::make('banned_from')
                        ->dateTime(),
                    TextEntry::make('banned_until')
                        ->dateTime()
                        ->default('Permanent'),
                    TextEntry::make('admin.email')
                        ->label('Banned By'),
                    TextEntry::make('created_at')
                        ->dateTime(),
                ]),
        ]);
    }
}
