<?php

declare(strict_types=1);

namespace Modules\Booking\Filament\Panels\Admin\Resources\BookingResource;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;

final class BookingFormSchema
{
    public static function make(Form $form): Form
    {
        return $form->schema([
            Section::make()
                ->columns(2)
                ->schema([
                    Select::make('client_id')
                        ->relationship('client', 'email')
                        ->required(),
                    Select::make('chair_id')
                        ->relationship('chair', 'label')
                        ->required(),
                    Select::make('barber_id')
                        ->relationship('barber', 'email')
                        ->required(),
                    DateTimePicker::make('time_slot')
                        ->required(),
                    Select::make('status')
                        ->options([
                            'pending' => 'Pending',
                            'confirmed' => 'Confirmed',
                            'in_progress' => 'In Progress',
                            'completed' => 'Completed',
                            'cancelled' => 'Cancelled',
                        ])
                        ->required(),
                    KeyValue::make('at_home_location'),
                ]),
        ]);
    }
}
