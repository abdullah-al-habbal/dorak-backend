<?php
declare(strict_types=1);

namespace Modules\OfferedService\Filament\Panels\Admin\Resources\OfferedServiceResource;

use Filament\Tables\Actions\DeleteAction;
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
                    ->label('Name')
                    ->searchable(),
                TextColumn::make('serviceable_type')
                    ->label('Entity'),
                TextColumn::make('price')
                    ->sortable(),
                TextColumn::make('duration')
                    ->suffix(' min'),
                IconColumn::make('at_home')
                    ->boolean(),
                IconColumn::make('active')
                    ->boolean(),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([]);
    }
}
