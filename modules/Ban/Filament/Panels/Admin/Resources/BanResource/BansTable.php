<?php
declare(strict_types=1);

namespace Modules\Ban\Filament\Panels\Admin\Resources\BanResource;

use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class BansTable
{
    public static function make(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('bannable_type')
                    ->label('Type')
                    ->searchable(),
                TextColumn::make('bannable_id')
                    ->label('Entity ID')
                    ->searchable(),
                TextColumn::make('reason')
                    ->limit(30),
                TextColumn::make('banned_from')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('banned_until')
                    ->dateTime()
                    ->placeholder('Permanent'),
                TextColumn::make('admin.email')
                    ->label('Banned By'),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([]);
    }
}
