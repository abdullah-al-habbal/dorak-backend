<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Filament\Panels\Admin\Resources\ServiceCatalogItemResource;

use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

final class ServiceCatalogItemsTable
{
    public static function make(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name.en')
                    ->label('Name')
                    ->searchable(),
                TextColumn::make('category.name.en')
                    ->label('Category'),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('sku')
                    ->searchable(),
                ToggleColumn::make('is_active'),
                TextColumn::make('created_at')
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
