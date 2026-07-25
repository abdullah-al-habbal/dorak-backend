<?php

declare(strict_types=1);

namespace Modules\Chair\Filament\Panels\Branch\Resources\ChairResource;

use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class ChairsTable
{
    public static function make(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label'),
                TextColumn::make('barber.name.en')
                    ->label('Barber')
                    ->placeholder('Unassigned'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'available' => 'success',
                        'occupied' => 'warning',
                        'maintenance' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([]);
    }
}
