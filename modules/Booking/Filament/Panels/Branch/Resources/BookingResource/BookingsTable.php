<?php

declare(strict_types=1);

namespace Modules\Booking\Filament\Panels\Branch\Resources\BookingResource;

use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class BookingsTable
{
    public static function make(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('client.name.en')
                    ->label('Client'),
                TextColumn::make('barber.name.en')
                    ->label('Barber'),
                TextColumn::make('chair.label')
                    ->label('Chair'),
                TextColumn::make('time_slot')
                    ->dateTime(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'confirmed' => 'success',
                        'completed' => 'info',
                        'canceled' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([]);
    }
}
