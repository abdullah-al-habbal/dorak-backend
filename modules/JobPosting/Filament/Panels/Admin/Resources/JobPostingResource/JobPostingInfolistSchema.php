<?php
declare(strict_types=1);

namespace Modules\JobPosting\Filament\Panels\Admin\Resources\JobPostingResource;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

final class JobPostingInfolistSchema
{
    public static function make(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make()
                ->columns(2)
                ->schema([
                    TextEntry::make('title.en')
                        ->label('Title (English)'),
                    TextEntry::make('title.ar')
                        ->label('Title (Arabic)'),
                    TextEntry::make('branch.name.en')
                        ->label('Branch'),
                    TextEntry::make('status')
                        ->badge()
                        ->color(fn (string $state): string => $state === 'open' ? 'success' : 'danger'),
                    TextEntry::make('description.en')
                        ->label('Description')
                        ->html(),
                    TextEntry::make('created_at')
                        ->dateTime(),
                ]),
        ]);
    }
}
