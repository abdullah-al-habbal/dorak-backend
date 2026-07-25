<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\Filament\Panels\Branch\Resources\BarberAffiliationResource;

use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\BarberAffiliation\Enums\AffiliationStatus;

final class BarberAffiliationsTable
{
    public static function make(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('barber.name.en')
                    ->label('Barber'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'accepted' => 'success',
                        'rejected' => 'danger',
                        'terminated' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('invited_at')
                    ->dateTime()
                    ->label('Affiliated'),
            ])
            ->actions([
                ViewAction::make(),
                Action::make('accept')
                    ->label('Accept')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record): bool => $record->status === AffiliationStatus::Pending)
                    ->action(fn ($record) => $record->update([
                        'status' => AffiliationStatus::Accepted,
                        'accepted_at' => now(),
                    ])),
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn ($record): bool => $record->status === AffiliationStatus::Pending)
                    ->action(fn ($record) => $record->update([
                        'status' => AffiliationStatus::Rejected,
                    ])),
            ])
            ->bulkActions([]);
    }
}
