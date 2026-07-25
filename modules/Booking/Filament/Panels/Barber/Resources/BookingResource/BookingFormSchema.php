<?php

declare(strict_types=1);

namespace Modules\Booking\Filament\Panels\Barber\Resources\BookingResource;

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
            Section::make('Booking Details')
                ->schema([
                    Select::make('status')
                        ->options(collect(BookingStatus::cases())->mapWithKeys(fn ($s) => [$s->value => $s->label()]))
                        ->required(),
                    Textarea::make('notes')
                        ->rows(3),
                ]),
        ]);
    }
}
