<?php

declare(strict_types=1);

namespace Modules\JobPosting\Filament\Panels\Barber\Resources\ApplicationResource;

use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\JobPosting\Enums\ApplicationStatus;

final class ApplicationTable
{
    public static function make(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('jobPosting.title.en')
                    ->label('Job Title'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (ApplicationStatus $state): string => match ($state) {
                        ApplicationStatus::Submitted => 'warning',
                        ApplicationStatus::Reviewed => 'info',
                        ApplicationStatus::Accepted => 'success',
                        ApplicationStatus::Rejected => 'danger',
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Applied'),
            ])
            ->actions([
                ViewAction::make(),
            ])
            ->bulkActions([]);
    }
}
