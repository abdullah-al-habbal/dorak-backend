<?php

declare(strict_types=1);

namespace Modules\Review\Filament\Panels\Barber\Resources\ReviewResource;

use Filament\Forms\Form;

final class ReviewFormSchema
{
    public static function make(Form $form): Form
    {
        return $form->schema([]);
    }
}
