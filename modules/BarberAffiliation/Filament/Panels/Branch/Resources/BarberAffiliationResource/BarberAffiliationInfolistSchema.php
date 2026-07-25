<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\Filament\Panels\Branch\Resources\BarberAffiliationResource;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

final class BarberAffiliationInfolistSchema
{
    public static function make(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Affiliation')
                ->columns(2)
                ->schema([
                    TextEntry::make('barber.name.en')
                        ->label('Barber'),
                    TextEntry::make('status')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'pending' => 'warning',
                            'accepted' => 'success',
                            'rejected' => 'danger',
                            'terminated' => 'gray',
                            default => 'gray',
                        }),
                    TextEntry::make('commission_rate')
                        ->label('Commission Rate (%)'),
                    TextEntry::make('invited_at')
                        ->dateTime()
                        ->label('Invited'),
                    TextEntry::make('accepted_at')
                        ->dateTime()
                        ->label('Accepted'),
                    TextEntry::make('terminated_at')
                        ->dateTime()
                        ->label('Terminated'),
                ]),
        ]);
    }
}
