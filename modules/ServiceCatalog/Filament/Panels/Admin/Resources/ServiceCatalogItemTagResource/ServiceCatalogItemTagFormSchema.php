<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Filament\Panels\Admin\Resources\ServiceCatalogItemTagResource;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;

final class ServiceCatalogItemTagFormSchema
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
                    TextInput::make('group'),
                    Toggle::make('is_active')
                        ->default(true),
                ]),
        ]);
    }
}
