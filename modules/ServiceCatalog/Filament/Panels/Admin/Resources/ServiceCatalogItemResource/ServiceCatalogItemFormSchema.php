<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Filament\Panels\Admin\Resources\ServiceCatalogItemResource;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Modules\ServiceCatalog\Enums\FormalityEnum;
use Modules\ServiceCatalog\Enums\MaintenanceLevelEnum;
use Modules\ServiceCatalog\Enums\StylePeriodEnum;

final class ServiceCatalogItemFormSchema
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
                    TextInput::make('sku')
                        ->unique(ignoreRecord: true),
                    Select::make('category_id')
                        ->relationship('category', 'name.en')
                        ->required(),
                    Select::make('maintenance_level')
                        ->options(MaintenanceLevelEnum::class),
                    Select::make('style_period')
                        ->options(StylePeriodEnum::class),
                    Select::make('formality')
                        ->options(FormalityEnum::class),
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
