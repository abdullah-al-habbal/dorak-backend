<?php

declare(strict_types=1);

namespace Modules\Language\Filament\Panels\Admin\Resources\LanguageResource;

use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class LanguagesTable
{
    public static function make(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->searchable(),
                TextColumn::make('name.en')
                    ->label('Name')
                    ->searchable(),
                TextColumn::make('direction')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'rtl' ? 'info' : 'gray'),
                IconColumn::make('is_default')
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
