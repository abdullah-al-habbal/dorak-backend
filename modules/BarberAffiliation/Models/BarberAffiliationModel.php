<?php

// modules/BarberAffiliation/Models/BarberAffiliationModel.php
declare(strict_types=1);

namespace Modules\BarberAffiliation\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Barber\Models\BarberModel;
use Modules\BarberAffiliation\Enums\AffiliableType;
use Modules\BarberAffiliation\Enums\AffiliationStatus;

#[Fillable([
    'barber_id',
    'affiliable_id',
    'affiliable_type',
    'status',
    'commission_rate',
    'invited_at',
    'accepted_at',
    'terminated_at',
])]
#[Table('barber_affiliations')]
class BarberAffiliationModel extends Model
{
    use HasFactory;
    use HasUuids;

    protected function casts(): array
    {
        return [
            'status' => AffiliationStatus::class,
            'affiliable_type' => AffiliableType::class,
            'commission_rate' => 'decimal:2',
            'invited_at' => 'datetime',
            'accepted_at' => 'datetime',
            'terminated_at' => 'datetime',
        ];
    }

    public function barber(): BelongsTo
    {
        return $this->belongsTo(BarberModel::class, 'barber_id');
    }

    public function affiliable(): MorphTo
    {
        return $this->morphTo();
    }
}
