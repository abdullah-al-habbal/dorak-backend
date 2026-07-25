<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\Filament\Panels\Barber\Resources\BarberAffiliationResource;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Modules\BarberAffiliation\Enums\AffiliationStatus;

final class BarberAffiliationInfolistSchema
{
    public static function make(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Affiliation Details')
                ->columns(2)
                ->schema([
                    TextEntry::make('affiliable')
                        ->label('Branch/Brand'),
                    TextEntry::make('status')
                        ->badge()
                        ->color(fn (AffiliationStatus $state): string => match ($state) {
                            AffiliationStatus::Pending => 'warning',
                            AffiliationStatus::Accepted => 'success',
                            AffiliationStatus::Rejected => 'danger',
                            AffiliationStatus::Terminated => 'danger',
                        }),
                    TextEntry::make('commission_rate')
                        ->label('Commission Rate'),
                    TextEntry::make('invited_at')
                        ->dateTime(),
                    TextEntry::make('accepted_at')
                        ->dateTime(),
                    TextEntry::make('terminated_at')
                        ->dateTime(),
                    TextEntry::make('created_at')
                        ->dateTime(),
                ]),
        ]);
    }
}
