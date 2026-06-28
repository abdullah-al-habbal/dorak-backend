<?php
// modules/Preference/Models/PreferenceModel.php
declare(strict_types=1);

namespace Modules\Preference\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PreferenceModel extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'preferences';

    protected $fillable = [
        'preferenceable_id',
        'preferenceable_type',
        'preferred_language',
        'notification_enabled',
        'display_currency_id',
        'theme',
        'price_display_mode',
    ];

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
