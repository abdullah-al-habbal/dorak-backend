<?php
declare(strict_types=1);

namespace Modules\Brand\Filament\Panels\Admin\Resources\BrandResource;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

final class BrandInfolistSchema
{
    public static function make(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make()
                ->columns(2)
                ->schema([
                    TextEntry::make('name.en')
                        ->label('Name (English)'),
                    TextEntry::make('name.ar')
                        ->label('Name (Arabic)'),
                    TextEntry::make('owner.email')
                        ->label('Owner'),
                    TextEntry::make('baseCurrency.code')
                        ->label('Base Currency'),
                    TextEntry::make('description.en')
                        ->label('Description')
                        ->html(),
                    TextEntry::make('created_at')
                        ->dateTime(),
                ]),
        ]);
    }
}
