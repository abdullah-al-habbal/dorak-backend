<?php

declare(strict_types=1);

namespace Modules\Currency\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Currency\Models\CurrencyModel;
use Modules\Currency\Models\ExchangeRateModel;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $syp = CurrencyModel::create([
            'code'       => 'SYP',
            'name'       => ['en' => 'Syrian Pound', 'ar' => 'الليرة السورية'],
            'symbol'     => '£',
            'is_default' => true,
        ]);

        $usd = CurrencyModel::create([
            'code'       => 'USD',
            'name'       => ['en' => 'US Dollar', 'ar' => 'الدولار الأمريكي'],
            'symbol'     => '$',
            'is_default' => false,
        ]);

        ExchangeRateModel::create([
            'from_currency_id' => $syp->id,
            'to_currency_id'   => $usd->id,
            'rate'             => 0.000067,
            'effective_at'     => now(),
        ]);
    }
}
