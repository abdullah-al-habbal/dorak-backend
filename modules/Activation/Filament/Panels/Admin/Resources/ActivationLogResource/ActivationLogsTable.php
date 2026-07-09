<?php
declare(strict_types=1);

namespace Modules\Activation\Filament\Panels\Admin\Resources\ActivationLogResource;

use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class ActivationLogsTable
{
    public static function make(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('activable_type')
                    ->label('Type')
                    ->searchable(),
                TextColumn::make('activable_id')
                    ->label('Entity ID')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state): string => $state?->value === 'enabled' ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state): string => $state?->value ?? 'unknown'),
                TextColumn::make('admin.email')
                    ->label('Admin'),
                TextColumn::make('activated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                ViewAction::make(),
            ])
            ->bulkActions([]);
    }
}
