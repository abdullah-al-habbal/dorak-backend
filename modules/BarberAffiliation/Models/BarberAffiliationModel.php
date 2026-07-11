<?php

// modules/BarberAffiliation/Models/BarberAffiliationModel.php
declare(strict_types=1);

namespace Modules\BarberAffiliation\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Barber\Models\BarberModel;

class BarberAffiliationModel extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'barber_affiliations';

    protected $fillable = [
        'barber_id',
        'affiliable_id',
        'affiliable_type',
        'status',
        'commission_rate',
        'invited_at',
        'accepted_at',
        'terminated_at',
    ];

    protected function casts(): array
    {
        return [
            'commission_rate' => 'decimal:2',
            'invited_at' => 'datetime',
            'accepted_at' => 'datetime',
            'terminated_at' => 'datetime',
        ];
    }

    public function barber(): BelongsTo
    {
        return $this->belongsTo(BarberModel::class);
    }

    public function affiliable(): MorphTo
    {
        return $this->morphTo();
    }
}
