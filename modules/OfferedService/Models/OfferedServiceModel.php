<?php

// modules/OfferedService/Models/OfferedServiceModel.php
declare(strict_types=1);

namespace Modules\OfferedService\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Currency\Models\CurrencyModel;
use Spatie\Translatable\HasTranslations;

class OfferedServiceModel extends Model
{
    use HasFactory;
    use HasTranslations;
    use HasUuids;

    protected $table = 'offered_services';

    protected $fillable = [
        'serviceable_id',
        'serviceable_type',
        'name',
        'description',
        'price',
        'currency_id',
        'duration',
        'at_home',
        'active',
    ];

    /** @phpstan-ignore-next-line */
    public array $translatable = ['name', 'description'];

    protected function casts(): array
    {
        return [
            'name' => 'array',
            'description' => 'array',
            'price' => 'decimal:2',
            'at_home' => 'boolean',
            'active' => 'boolean',
            'duration' => 'integer',
        ];
    }

    public function serviceable(): MorphTo
    {
        return $this->morphTo();
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(CurrencyModel::class, 'currency_id');
    }
}
