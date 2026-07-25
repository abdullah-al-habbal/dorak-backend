<?php

declare(strict_types=1);

namespace Modules\Booking\Filament\Panels\Barber\Resources\BookingResource;

use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Booking\Enums\BookingStatus;

final class BookingTable
{
    public static function make(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('client.name.en')
                    ->label('Client'),
                TextColumn::make('chair.name')
                    ->label('Chair'),
                TextColumn::make('time_slot')
                    ->dateTime(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (BookingStatus $state): string => match ($state) {
                        BookingStatus::Confirmed => 'success',
                        BookingStatus::Completed => 'info',
                        BookingStatus::Canceled => 'danger',
                    }),
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
