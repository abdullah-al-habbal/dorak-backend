<?php

declare(strict_types=1);

namespace Modules\JobPosting\Filament\Panels\Branch\Resources\JobPostingResource;

use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class JobPostingsTable
{
    public static function make(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title.en')
                    ->label('Title'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'open' => 'success',
                        'closed' => 'danger',
                        'archived' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('applications_count')
                    ->counts('applications')
                    ->label('Applications'),
                TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([]);
    }
}
