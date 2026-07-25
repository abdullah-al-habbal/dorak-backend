<?php

declare(strict_types=1);

namespace Modules\JobPosting\Filament\Panels\Branch\Resources\ApplicationResource;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

final class ApplicationInfolistSchema
{
    public static function make(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Application')
                ->columns(2)
                ->schema([
                    TextEntry::make('barber.name.en')
                        ->label('Barber'),
                    TextEntry::make('jobPosting.title.en')
                        ->label('Job Title'),
                    TextEntry::make('status')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'submitted' => 'warning',
                            'reviewed' => 'info',
                            'accepted' => 'success',
                            'rejected' => 'danger',
                            default => 'gray',
                        }),
                    TextEntry::make('created_at')
                        ->dateTime()
                        ->label('Applied'),
                ]),
            Section::make('Profile Snapshot')
                ->schema([
                    TextEntry::make('profile_snapshot')
                        ->label('Snapshot')
                        ->json(),
                ]),
        ]);
    }
}
