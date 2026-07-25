<?php

declare(strict_types=1);

namespace Modules\Booking\Filament\Panels\Branch\Resources\BookingResource;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Modules\Booking\Enums\BookingStatus;

final class BookingFormSchema
{
    public static function make(Form $form): Form
    {
        return $form->schema([
            Section::make('Booking')
                ->columns(2)
                ->schema([
                    Select::make('status')
                        ->options(BookingStatus::class)
                        ->required(),
                    Textarea::make('notes')
                        ->rows(3)
                        ->nullable(),
                ]),
        ]);
    }
}
