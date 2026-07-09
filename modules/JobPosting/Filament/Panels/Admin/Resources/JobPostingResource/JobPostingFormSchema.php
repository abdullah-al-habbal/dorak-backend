<?php
declare(strict_types=1);

namespace Modules\JobPosting\Filament\Panels\Admin\Resources\JobPostingResource;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

final class JobPostingFormSchema
{
    public static function make(Form $form): Form
    {
        return $form->schema([
            Section::make()
                ->columns(2)
                ->schema([
                    TextInput::make('title.en')
                        ->label('Title (English)')
                        ->required(),
                    TextInput::make('title.ar')
                        ->label('Title (Arabic)')
                        ->required(),
                    Select::make('branch_id')
                        ->relationship('branch', 'name.en')
                        ->required(),
                    Select::make('status')
                        ->options([
                            'open'   => 'Open',
                            'closed' => 'Closed',
                        ])
                        ->required(),
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
