<?php

declare(strict_types=1);

namespace Modules\Currency\Filament\Panels\Admin\Resources\ExchangeRateResource;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

final class ExchangeRateFormSchema
{
    public static function make(Form $form): Form
    {
        return $form->schema([
            Section::make()
                ->columns(2)
                ->schema([
                    Select::make('from_currency_id')
                        ->label('From Currency')
                        ->relationship('fromCurrency', 'code')
                        ->required(),
                    Select::make('to_currency_id')
                        ->label('To Currency')
                        ->relationship('toCurrency', 'code')
                        ->required(),
                    TextInput::make('rate')
                        ->numeric()
                        ->required()
                        ->step('0.000001'),
                    DateTimePicker::make('effective_at')
                        ->required()
                        ->default(now()),
                ]),
        ]);
    }
}
