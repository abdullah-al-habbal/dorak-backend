<?php
declare(strict_types=1);

namespace Modules\Ban\Filament\Panels\Admin\Resources\BanResource;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;

final class BanFormSchema
{
    public static function make(Form $form): Form
    {
        return $form->schema([
            Section::make()
                ->columns(2)
                ->schema([
                    MorphToSelect::make('bannable')
                        ->label('Entity')
                        ->types([
                            MorphToSelect\Type::make(\Modules\Client\Models\ClientModel::class)
                                ->titleAttribute('email'),
                            MorphToSelect\Type::make(\Modules\Barber\Models\BarberModel::class)
                                ->titleAttribute('email'),
                            MorphToSelect\Type::make(\Modules\Branch\Models\BranchModel::class)
                                ->titleAttribute('email'),
                        ])
                        ->required(),
                    Textarea::make('reason')
                        ->rows(3),
                    DateTimePicker::make('banned_from')
                        ->required()
                        ->default(now()),
                    DateTimePicker::make('banned_until')
                        ->helperText('Leave empty for permanent ban'),
                ]),
        ]);
    }
}
