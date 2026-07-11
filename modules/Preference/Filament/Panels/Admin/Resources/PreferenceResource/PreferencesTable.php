<?php

declare(strict_types=1);

namespace Modules\Preference\Filament\Panels\Admin\Resources\PreferenceResource;

use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class PreferencesTable
{
    public static function make(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('preferenceable_type')
                    ->label('Entity Type'),
                TextColumn::make('preferenceable_id')
                    ->label('Entity ID')
                    ->searchable(),
                TextColumn::make('preferred_language'),
                TextColumn::make('displayCurrency.code')
                    ->label('Currency'),
                IconColumn::make('notification_enabled')
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
