<?php

// modules/Activation/Filament/Actions/ToggleActivationAction.php
declare(strict_types=1);

namespace Modules\Activation\Filament\Actions;

use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Modules\Activation\Models\ActivationLogModel;

final class ToggleActivationAction
{
    public static function make(string $name = 'toggle_activation'): Action
    {
        return Action::make($name)
            ->label(fn (Model $record): string => $record->status === 'enabled' ? 'Disable' : 'Enable')
            ->color(fn (Model $record): string => $record->status === 'enabled' ? 'danger' : 'success')
            ->icon(fn (Model $record): string => $record->status === 'enabled' ? 'heroicon-o-no-symbol' : 'heroicon-o-check-circle')
            ->requiresConfirmation()
            ->action(function (Model $record): void {
                $newStatus = $record->status === 'enabled' ? 'disabled' : 'enabled';

                ActivationLogModel::create([
                    'activable_id' => $record->getKey(),
                    'activable_type' => $record->getMorphClass(),
                    'status' => $newStatus,
                    'admin_id' => auth('admin')->id(),
                    'activated_at' => now(),
                ]);

                Notification::make()
                    ->title($newStatus === 'enabled' ? 'Entity enabled' : 'Entity disabled')
                    ->success()
                    ->send();
            });
    }
}
