<?php
// modules/Currency/Models/CurrencyModel.php
declare(strict_types=1);

namespace Modules\Currency\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyModel extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'currencies';

    protected $fillable = [
        'code', 'name', 'symbol', 'is_default',
    ];

    protected function casts(): array
    {
        return [
            'name'       => 'array',
            'is_default' => 'boolean',
        ];
    }

    public function exchangeRatesFrom(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ExchangeRateModel::class, 'from_currency_id');
    }

    public function exchangeRatesTo(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ExchangeRateModel::class, 'to_currency_id');
    }
}
