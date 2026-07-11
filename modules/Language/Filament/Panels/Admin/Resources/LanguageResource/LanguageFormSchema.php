<?php

declare(strict_types=1);

namespace Modules\Language\Filament\Panels\Admin\Resources\LanguageResource;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;

final class LanguageFormSchema
{
    public static function make(Form $form): Form
    {
        return $form->schema([
            Section::make()
                ->columns(2)
                ->schema([
                    TextInput::make('code')
                        ->required()
                        ->maxLength(2)
                        ->unique(ignoreRecord: true),
                    TextInput::make('name.en')
                        ->label('Name (English)')
                        ->required(),
                    TextInput::make('name.ar')
                        ->label('Name (Arabic)')
                        ->required(),
                    Select::make('direction')
                        ->options([
                            'ltr' => 'Left to Right',
                            'rtl' => 'Right to Left',
                        ])
                        ->required(),
                    Toggle::make('is_default'),
                ]),
        ]);
    }
}
