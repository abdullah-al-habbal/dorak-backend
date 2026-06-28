<?php

declare(strict_types=1);

namespace Modules\Brand\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Brand\Models\BrandModel;
use Modules\Client\Models\ClientModel;
use Modules\Currency\Models\CurrencyModel;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $client = ClientModel::where('email', 'admin@dorak.sy')->first()
            ?? ClientModel::factory()->create(['email' => 'admin@dorak.sy']);

        $syp = CurrencyModel::where('code', 'SYP')->first()
            ?? CurrencyModel::factory()->create(['code' => 'SYP']);

        BrandModel::create([
            'owner_id'         => $client->id,
            'name'             => ['en' => 'Dorak Demo', 'ar' => 'دوراك التجريبي'],
            'description'      => ['en' => 'Demo brand for development', 'ar' => 'علامة تجريبية للتطوير'],
            'logo'             => null,
            'base_currency_id' => $syp->id,
        ]);
    }
}
