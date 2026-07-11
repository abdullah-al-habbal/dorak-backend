<?php

declare(strict_types=1);

namespace Modules\Chair\Filament\Panels\Admin\Resources\ChairResource;

use Filament\Tables\Actions\DeleteAction;
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
                TextColumn::make('label')
                    ->searchable(),
                TextColumn::make('branch.name.en')
                    ->label('Branch')
                    ->searchable(),
                TextColumn::make('barber.email')
                    ->label('Barber')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'occupied' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([]);
    }
}
