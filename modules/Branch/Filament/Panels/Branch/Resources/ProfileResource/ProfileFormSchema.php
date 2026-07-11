<?php

declare(strict_types=1);

namespace Modules\Branch\Filament\Panels\Branch\Resources\ProfileResource;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

final class ProfileFormSchema
{
    public static function make(Form $form): Form
    {
        return $form->schema([
            Section::make('Name')
                ->columns(2)
                ->schema([
                    TextInput::make('name.en')
                        ->label('Name (English)')
                        ->required(),
                    TextInput::make('name.ar')
                        ->label('Name (Arabic)')
                        ->required(),
                ]),
            Section::make('Account')
                ->schema([
                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true),
                    TextInput::make('password')
                        ->password()
                        ->dehydrated(fn (?string $state): bool => filled($state))
                        ->helperText('Leave empty to keep current password'),
                    TextInput::make('phone')
                        ->tel(),
                ]),
        ]);
    }
}
