<?php

declare(strict_types=1);

namespace Modules\JobPosting\Filament\Panels\Barber\Resources\ApplicationResource;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Modules\JobPosting\Enums\JobPostingStatusEnum;
use Modules\JobPosting\Models\JobPostingModel;

final class ApplicationFormSchema
{
    public static function make(Form $form): Form
    {
        return $form->schema([
            Section::make('Application')
                ->schema([
                    Select::make('job_posting_id')
                        ->label('Job Posting')
                        ->options(
                            JobPostingModel::query()
                                ->where('status', JobPostingStatusEnum::Open)
                                ->get()
                                ->mapWithKeys(fn ($jp) => [$jp->id => $jp->title['en'] ?? $jp->id])
                        )
                        ->searchable()
                        ->required(),
                    Textarea::make('profile_snapshot')
                        ->rows(5)
                        ->label('Profile Snapshot'),
                ]),
        ]);
    }
}
