<?php
declare(strict_types=1);

namespace Modules\Brand\Filament\Panels\Admin\Resources\BrandResource;

use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Activation\Filament\Actions\ToggleActivationAction;

final class BrandsTable
{
    public static function make(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name.en')
                    ->label('Name')
                    ->searchable(),
                TextColumn::make('owner.email')
                    ->label('Owner')
                    ->searchable(),
                TextColumn::make('baseCurrency.code')
                    ->label('Currency'),
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
