<?php

declare(strict_types=1);

namespace Modules\Barber\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Barber\Database\Factories\BarberPortfolioPhotoFactory;

#[Fillable(['barber_id', 'path', 'sort_order'])]
#[Table('barber_portfolio_photos')]
final class BarberPortfolioPhotoModel extends Model
{
    use HasFactory;
    use HasUuids;

    protected static function newFactory(): BarberPortfolioPhotoFactory
    {
        return BarberPortfolioPhotoFactory::new();
    }

    public function barber(): BelongsTo
    {
        return $this->belongsTo(BarberModel::class);
    }
}
