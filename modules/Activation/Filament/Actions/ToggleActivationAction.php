<?php

declare(strict_types=1);

namespace Modules\Activation\Filament\Actions;

use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Modules\Activation\Enums\ActivationStatusEnum;
use Modules\Activation\Models\ActivationLogModel;

final class ToggleActivationAction
{
    public static function make(string $name = 'toggle_activation'): Action
    {
        return Action::make($name)
            ->label(fn (Model $record): string => $record->status === ActivationStatusEnum::Enabled ? 'Disable' : 'Enable')
            ->color(fn (Model $record): string => $record->status === ActivationStatusEnum::Enabled ? 'danger' : 'success')
            ->icon(fn (Model $record): string => $record->status === ActivationStatusEnum::Enabled ? 'heroicon-o-no-symbol' : 'heroicon-o-check-circle')
            ->requiresConfirmation()
            ->action(function (Model $record): void {
                $newStatus = $record->status === ActivationStatusEnum::Enabled
                    ? ActivationStatusEnum::Disabled
                    : ActivationStatusEnum::Enabled;

                ActivationLogModel::create([
                    'activable_id' => $record->getKey(),
                    'activable_type' => $record->getMorphClass(),
                    'status' => $newStatus->value,
                    'admin_id' => auth('admin')->id(),
                    'activated_at' => now(),
                ]);

                Notification::make()
                    ->title($newStatus === ActivationStatusEnum::Enabled ? 'Entity enabled' : 'Entity disabled')
                    ->success()
                    ->send();
            });
    }
}
