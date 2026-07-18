<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Filament\Panels\Admin\Resources\ServiceCatalogCategoryResource;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;

final class ServiceCatalogCategoryFormSchema
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
                    TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true),
                    Toggle::make('is_active')
                        ->default(true),
                ]),
            Section::make('Description')
                ->schema([
                    TextInput::make('description.en')
                        ->label('Description (English)'),
                    TextInput::make('description.ar')
                        ->label('Description (Arabic)'),
                ]),
        ]);
    }
}
