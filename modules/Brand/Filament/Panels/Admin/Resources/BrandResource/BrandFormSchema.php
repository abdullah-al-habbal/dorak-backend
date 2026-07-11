<?php

declare(strict_types=1);

namespace Modules\Brand\Filament\Panels\Admin\Resources\BrandResource;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

final class BrandFormSchema
{
    public static function make(Form $form): Form
    {
        return $form->schema([
            Section::make()
                ->columns(2)
                ->schema([
                    TextInput::make('name.en')
                        ->label('Name (English)')
                        ->required(),
                    TextInput::make('name.ar')
                        ->label('Name (Arabic)')
                        ->required(),
                    Select::make('owner_id')
                        ->relationship('owner', 'email')
                        ->required(),
                    Select::make('base_currency_id')
                        ->relationship('baseCurrency', 'code')
                        ->required(),
                    TextInput::make('logo')
                        ->url(),
                ]),
            Section::make('Description')
                ->schema([
                    RichEditor::make('description.en')
                        ->label('Description (English)'),
                    RichEditor::make('description.ar')
                        ->label('Description (Arabic)'),
                ]),
        ]);
    }
}
