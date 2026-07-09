<?php
declare(strict_types=1);

namespace Modules\OfferedService\Filament\Panels\Admin\Resources\OfferedServiceResource;

use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;

final class OfferedServiceFormSchema
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
                    MorphToSelect::make('serviceable')
                        ->label('Entity')
                        ->types([
                            MorphToSelect\Type::make(\Modules\Brand\Models\BrandModel::class)
                                ->titleAttribute('name.en'),
                        ])
                        ->required(),
                    Select::make('currency_id')
                        ->relationship('currency', 'code')
                        ->required(),
                    TextInput::make('price')
                        ->numeric()
                        ->required()
                        ->step('0.01'),
                    TextInput::make('duration')
                        ->numeric()
                        ->required()
                        ->suffix('min'),
                    Toggle::make('at_home'),
                    Toggle::make('active')
                        ->default(true),
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
