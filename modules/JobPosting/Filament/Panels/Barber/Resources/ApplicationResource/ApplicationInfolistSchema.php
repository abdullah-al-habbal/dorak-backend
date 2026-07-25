<?php

declare(strict_types=1);

namespace Modules\JobPosting\Filament\Panels\Barber\Resources\ApplicationResource;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Modules\JobPosting\Enums\ApplicationStatus;

final class ApplicationInfolistSchema
{
    public static function make(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Application Details')
                ->columns(2)
                ->schema([
                    TextEntry::make('jobPosting.title.en')
                        ->label('Job Posting'),
                    TextEntry::make('status')
                        ->badge()
                        ->color(fn (ApplicationStatus $state): string => match ($state) {
                            ApplicationStatus::Submitted => 'warning',
                            ApplicationStatus::Reviewed => 'info',
                            ApplicationStatus::Accepted => 'success',
                            ApplicationStatus::Rejected => 'danger',
                        }),
                    TextEntry::make('profile_snapshot')
                        ->label('Profile Snapshot'),
                    TextEntry::make('created_at')
                        ->dateTime(),
                ]),
        ]);
    }
}
