<?php

// modules/Chair/Models/ChairModel.php
declare(strict_types=1);

namespace Modules\Chair\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Barber\Models\BarberModel;
use Modules\Booking\Models\BookingModel;
use Modules\Branch\Models\BranchModel;
use Modules\Chair\Enums\ChairStatus;

class ChairModel extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'chairs';

    protected $fillable = [
        'branch_id', 'barber_id', 'label', 'ui_metadata', 'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => ChairStatus::class,
            'ui_metadata' => 'array',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(BranchModel::class, 'branch_id');
    }

    public function barber(): BelongsTo
    {
        return $this->belongsTo(BarberModel::class, 'barber_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(BookingModel::class, 'chair_id');
    }
}
