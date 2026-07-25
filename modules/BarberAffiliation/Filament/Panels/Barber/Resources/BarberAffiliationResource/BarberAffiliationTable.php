<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\Filament\Panels\Barber\Resources\BarberAffiliationResource;

use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\BarberAffiliation\Enums\AffiliationStatus;

final class BarberAffiliationTable
{
    public static function make(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('affiliable')
                    ->label('Branch/Brand'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (AffiliationStatus $state): string => match ($state) {
                        AffiliationStatus::Pending => 'warning',
                        AffiliationStatus::Accepted => 'success',
                        AffiliationStatus::Rejected => 'danger',
                        AffiliationStatus::Terminated => 'danger',
                    }),
                TextColumn::make('commission_rate')
                    ->label('Commission'),
                TextColumn::make('invited_at')
                    ->dateTime(),
                TextColumn::make('accepted_at')
                    ->dateTime(),
            ])
            ->actions([
                ViewAction::make(),
            ])
            ->bulkActions([]);
    }
}
