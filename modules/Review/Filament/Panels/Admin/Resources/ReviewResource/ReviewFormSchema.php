<?php

declare(strict_types=1);

namespace Modules\Review\Filament\Panels\Admin\Resources\ReviewResource;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

final class ReviewFormSchema
{
    public static function make(Form $form): Form
    {
        return $form->schema([
            Section::make()
                ->columns(2)
                ->schema([
                    Select::make('booking_id')
                        ->relationship('booking', 'id')
                        ->required(),
                    TextInput::make('author_id')
                        ->required(),
                    TextInput::make('author_type')
                        ->required(),
                    TextInput::make('subject_id')
                        ->required(),
                    TextInput::make('subject_type')
                        ->required(),
                    TextInput::make('rating')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(5)
                        ->required(),
                    Textarea::make('comment')
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
