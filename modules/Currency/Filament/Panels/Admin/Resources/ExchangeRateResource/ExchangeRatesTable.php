<?php
declare(strict_types=1);

namespace Modules\Currency\Filament\Panels\Admin\Resources\ExchangeRateResource;

use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class ExchangeRatesTable
{
    public static function make(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('fromCurrency.code')
                    ->label('From')
                    ->searchable(),
                TextColumn::make('toCurrency.code')
                    ->label('To')
                    ->searchable(),
                TextColumn::make('rate')
                    ->sortable(),
                TextColumn::make('effective_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([]);
    }
}
