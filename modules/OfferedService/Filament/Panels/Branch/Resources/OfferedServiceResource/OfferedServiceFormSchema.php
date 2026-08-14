<?php

declare(strict_types=1);

namespace Modules\OfferedService\Filament\Panels\Branch\Resources\OfferedServiceResource;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

final class OfferedServiceFormSchema
{
    public static function make(Form $form): Form
    {
        return $form->schema([
            Section::make('Service Details')
                ->columns(2)
                ->schema([
                    TextInput::make('name.en')
                        ->label('Name (English)')
                        ->required(),
                    TextInput::make('name.ar')
                        ->label('Name (Arabic)')
                        ->required(),
                    TextInput::make('price')
                        ->numeric()
                        ->required(),
                    Select::make('currency_id')
                        ->relationship('currency', 'code')
                        ->required(),
                    TextInput::make('duration')
                        ->label('Duration (minutes)')
                        ->numeric()
                        ->required(),
                    Checkbox::make('at_home')
                        ->label('Available at Home'),
                    Checkbox::make('active')
                        ->label('Active')
                        ->default(true),
                ]),
        ]);
    }
}
