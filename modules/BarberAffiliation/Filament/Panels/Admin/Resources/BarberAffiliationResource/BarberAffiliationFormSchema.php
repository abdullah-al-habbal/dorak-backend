<?php
declare(strict_types=1);

namespace Modules\BarberAffiliation\Filament\Panels\Admin\Resources\BarberAffiliationResource;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

final class BarberAffiliationFormSchema
{
    public static function make(Form $form): Form
    {
        return $form->schema([
            Section::make()
                ->columns(2)
                ->schema([
                    Select::make('barber_id')
                        ->relationship('barber', 'email')
                        ->required(),
                    MorphToSelect::make('affiliable')
                        ->label('Entity')
                        ->types([
                            MorphToSelect\Type::make(\Modules\Brand\Models\BrandModel::class)
                                ->titleAttribute('name.en'),
                        ])
                        ->required(),
                    Select::make('status')
                        ->options([
                            'pending'    => 'Pending',
                            'accepted'   => 'Accepted',
                            'terminated' => 'Terminated',
                        ])
                        ->required(),
                    TextInput::make('commission_rate')
                        ->numeric()
                        ->step('0.01')
                        ->suffix('%'),
                    DateTimePicker::make('invited_at'),
                    DateTimePicker::make('accepted_at'),
                    DateTimePicker::make('terminated_at'),
                ]),
        ]);
    }
}
