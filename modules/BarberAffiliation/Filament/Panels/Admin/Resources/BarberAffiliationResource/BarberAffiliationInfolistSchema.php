<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\Filament\Panels\Admin\Resources\BarberAffiliationResource;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

final class BarberAffiliationInfolistSchema
{
    public static function make(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make()
                ->columns(2)
                ->schema([
                    TextEntry::make('barber.email')
                        ->label('Barber'),
                    TextEntry::make('affiliable_type')
                        ->label('Entity Type'),
                    TextEntry::make('affiliable_id')
                        ->label('Entity ID'),
                    TextEntry::make('status')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'accepted' => 'success',
                            'terminated' => 'danger',
                            default => 'warning',
                        }),
                    TextEntry::make('commission_rate'),
                    TextEntry::make('invited_at')
                        ->dateTime(),
                    TextEntry::make('accepted_at')
                        ->dateTime(),
                    TextEntry::make('terminated_at')
                        ->dateTime(),
                ]),
        ]);
    }
}
