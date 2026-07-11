<?php

// modules/Barber/Filament/Panels/Admin/Resources/BarberResource/BarbersTable.php
declare(strict_types=1);

namespace Modules\Barber\Filament\Panels\Admin\Resources\BarberResource;

use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Activation\Filament\Actions\ToggleActivationAction;

final class BarbersTable
{
    public static function make(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name.en')
                    ->label('Name')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'enabled' => 'success',
                        'disabled' => 'danger',
                        default => 'warning',
                    }),
                IconColumn::make('is_freelancer')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                ToggleActivationAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([]);
    }
}
