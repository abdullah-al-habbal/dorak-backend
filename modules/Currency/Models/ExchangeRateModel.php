<?php

// modules/Currency/Models/ExchangeRateModel.php
declare(strict_types=1);

namespace Modules\Currency\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['from_currency_id', 'to_currency_id', 'rate', 'effective_at'])]
#[Table('exchange_rates')]
class ExchangeRateModel extends Model
{
    use HasFactory;
    use HasUuids;

    protected function casts(): array
    {
        return [
            'rate' => 'decimal:6',
            'effective_at' => 'datetime',
        ];
    }

    public function fromCurrency(): BelongsTo
    {
        return $this->belongsTo(CurrencyModel::class, 'from_currency_id');
    }

    public function toCurrency(): BelongsTo
    {
        return $this->belongsTo(CurrencyModel::class, 'to_currency_id');
    }
}
