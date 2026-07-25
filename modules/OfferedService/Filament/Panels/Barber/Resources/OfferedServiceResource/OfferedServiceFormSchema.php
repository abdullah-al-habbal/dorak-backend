<?php

declare(strict_types=1);

namespace Modules\OfferedService\Filament\Panels\Barber\Resources\OfferedServiceResource;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Modules\Currency\Models\CurrencyModel;

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
                        ->numeric()
                        ->label('Duration (minutes)')
                        ->required(),
                    Toggle::make('at_home')
                        ->label('Available at Home'),
                    Toggle::make('active')
                        ->label('Active')
                        ->default(true),
                ]),
        ]);
    }
}
