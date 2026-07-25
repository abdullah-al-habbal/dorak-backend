<?php

declare(strict_types=1);

namespace Modules\OfferedService\Filament\Panels\Branch\Resources\OfferedServiceResource;

use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class OfferedServicesTable
{
    public static function make(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name.en')
                    ->label('Name'),
                TextColumn::make('price')
                    ->money(fn ($record): string => $record->currency->code ?? 'USD'),
                TextColumn::make('duration')
                    ->label('Minutes'),
                IconColumn::make('at_home')
                    ->boolean(),
                IconColumn::make('active')
                    ->boolean(),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([]);
    }
}
