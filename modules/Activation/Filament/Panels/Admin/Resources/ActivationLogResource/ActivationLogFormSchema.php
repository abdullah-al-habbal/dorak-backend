<?php

declare(strict_types=1);

namespace Modules\Activation\Filament\Panels\Admin\Resources\ActivationLogResource;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Modules\Activation\Enums\ActivationStatusEnum;

final class ActivationLogFormSchema
{
    public static function make(Form $form): Form
    {
        return $form->schema([
            Section::make()
                ->columns(2)
                ->schema([
                    TextInput::make('activable_id')
                        ->required(),
                    TextInput::make('activable_type')
                        ->required(),
                    Select::make('status')
                        ->options(ActivationStatusEnum::class)
                        ->required(),
                    Textarea::make('reason')
                        ->columnSpanFull(),
                    Select::make('admin_id')
                        ->relationship('admin', 'email')
                        ->nullable(),
                    DateTimePicker::make('activated_at')
                        ->required(),
                    DateTimePicker::make('expires_at')
                        ->nullable(),
                ]),
        ]);
    }
}
