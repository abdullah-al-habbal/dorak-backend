<?php

declare(strict_types=1);

namespace Modules\Barber\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Barber\Database\Factories\BarberScheduleFactory;

#[Fillable(['barber_id', 'day_of_week', 'start_time', 'end_time', 'is_active'])]
#[Table('barber_schedules')]
final class BarberScheduleModel extends Model
{
    use HasFactory;
    use HasUuids;

    protected static function newFactory(): BarberScheduleFactory
    {
        return BarberScheduleFactory::new();
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function barber(): BelongsTo
    {
        return $this->belongsTo(BarberModel::class);
    }
}
