<?php
declare(strict_types=1);

namespace Modules\Booking\Filament\Panels\Admin\Resources\BookingResource;

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
                TextColumn::make('client.email')
                    ->label('Client')
                    ->searchable(),
                TextColumn::make('barber.email')
                    ->label('Barber')
                    ->searchable(),
                TextColumn::make('chair.label')
                    ->label('Chair'),
                TextColumn::make('time_slot')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'confirmed'   => 'success',
                        'completed'   => 'info',
                        'cancelled'   => 'danger',
                        'in_progress' => 'warning',
                        default       => 'gray',
                    }),
            ])
            ->defaultSort('time_slot', 'desc')
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([]);
    }
}
