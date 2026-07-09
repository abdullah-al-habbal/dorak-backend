<?php
declare(strict_types=1);

namespace Modules\JobPosting\Filament\Panels\Admin\Resources\ApplicationResource;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;

final class ApplicationFormSchema
{
    public static function make(Form $form): Form
    {
        return $form->schema([
            Section::make()
                ->columns(2)
                ->schema([
                    Select::make('job_posting_id')
                        ->relationship('jobPosting', 'title.en')
                        ->required(),
                    Select::make('barber_id')
                        ->relationship('barber', 'email')
                        ->required(),
                    Select::make('status')
                        ->options([
                            'pending'   => 'Pending',
                            'reviewed'  => 'Reviewed',
                            'accepted'  => 'Accepted',
                            'rejected'  => 'Rejected',
                        ])
                        ->required(),
                    KeyValue::make('profile_snapshot'),
                ]),
        ]);
    }
}
