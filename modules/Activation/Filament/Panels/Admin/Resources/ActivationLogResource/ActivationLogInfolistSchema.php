<?php

declare(strict_types=1);

namespace Modules\Activation\Filament\Panels\Admin\Resources\ActivationLogResource;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

final class ActivationLogInfolistSchema
{
    public static function make(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make()
                ->columns(2)
                ->schema([
                    TextEntry::make('activable_type')
                        ->label('Entity Type'),
                    TextEntry::make('activable_id')
                        ->label('Entity ID'),
                    TextEntry::make('status')
                        ->badge()
                        ->color(fn (string $state): string => $state === 'enabled' ? 'success' : 'danger'),
                    TextEntry::make('reason'),
                    TextEntry::make('admin.email')
                        ->label('Admin'),
                    TextEntry::make('activated_at')
                        ->dateTime(),
                    TextEntry::make('expires_at')
                        ->dateTime(),
                ]),
        ]);
    }
}
