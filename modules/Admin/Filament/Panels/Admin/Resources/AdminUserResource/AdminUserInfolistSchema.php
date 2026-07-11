<?php

// modules/Admin/Filament/Panels/Admin/Resources/AdminUserResource/AdminUserInfolistSchema.php
declare(strict_types=1);

namespace Modules\Admin\Filament\Panels\Admin\Resources\AdminUserResource;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

final class AdminUserInfolistSchema
{
    public static function make(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Admin Details')
                ->columns(2)
                ->schema([
                    TextEntry::make('name.en')
                        ->label('Name (English)'),
                    TextEntry::make('name.ar')
                        ->label('Name (Arabic)'),
                    TextEntry::make('email'),
                    TextEntry::make('created_at')
                        ->dateTime(),
                    TextEntry::make('updated_at')
                        ->dateTime(),
                ]),
        ]);
    }
}
