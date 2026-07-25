<?php

declare(strict_types=1);

namespace Modules\JobPosting\Filament\Panels\Branch\Resources\JobPostingResource;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

final class JobPostingInfolistSchema
{
    public static function make(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Job Posting')
                ->columns(2)
                ->schema([
                    TextEntry::make('title.en')
                        ->label('Title (English)'),
                    TextEntry::make('title.ar')
                        ->label('Title (Arabic)'),
                    TextEntry::make('description.en')
                        ->label('Description (English)')
                        ->columnSpanFull(),
                    TextEntry::make('description.ar')
                        ->label('Description (Arabic)')
                        ->columnSpanFull(),
                    TextEntry::make('status')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'open' => 'success',
                            'closed' => 'danger',
                            'archived' => 'gray',
                            default => 'gray',
                        }),
                    TextEntry::make('applications_count')
                        ->counts('applications')
                        ->label('Applications'),
                    TextEntry::make('created_at')
                        ->dateTime(),
                ]),
        ]);
    }
}
