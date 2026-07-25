<?php

declare(strict_types=1);

namespace Modules\JobPosting\Filament\Panels\Branch\Resources\JobPostingResource;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Modules\JobPosting\Enums\JobPostingStatusEnum;

final class JobPostingFormSchema
{
    public static function make(Form $form): Form
    {
        return $form->schema([
            Section::make('Job Posting')
                ->columns(2)
                ->schema([
                    TextInput::make('title.en')
                        ->label('Title (English)')
                        ->required(),
                    TextInput::make('title.ar')
                        ->label('Title (Arabic)')
                        ->required(),
                    Textarea::make('description.en')
                        ->label('Description (English)')
                        ->rows(4)
                        ->required(),
                    Textarea::make('description.ar')
                        ->label('Description (Arabic)')
                        ->rows(4)
                        ->required(),
                    Select::make('status')
                        ->options(JobPostingStatusEnum::class)
                        ->required()
                        ->default(JobPostingStatusEnum::Open),
                ]),
        ]);
    }
}
