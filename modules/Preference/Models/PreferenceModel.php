<?php

// modules/Preference/Models/PreferenceModel.php
declare(strict_types=1);

namespace Modules\Preference\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'preferenceable_id',
    'preferenceable_type',
    'preferred_language',
    'notification_enabled',
    'display_currency_id',
    'theme',
    'price_display_mode',
])]
#[Table('preferences')]
class PreferenceModel extends Model
{
    use HasFactory;
    use HasUuids;

    protected function casts(): array
    {
        return [
            'notification_enabled' => 'boolean',
        ];
    }

    public function preferenceable(): MorphTo
    {
        return $this->morphTo();
    }
}
