<?php

declare(strict_types=1);

namespace Modules\OfferedService\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Brand\Models\BrandModel;
use Modules\Currency\Models\CurrencyModel;
use Modules\OfferedService\Models\OfferedServiceModel;

class OfferedServiceSeeder extends Seeder
{
    public function run(): void
    {
        $syp = CurrencyModel::where('code', 'SYP')->first()
            ?? CurrencyModel::factory()->create(['code' => 'SYP']);

        $brand = BrandModel::first()
            ?? BrandModel::factory()->create();

        OfferedServiceModel::create([
            'serviceable_id'   => $brand->id,
            'serviceable_type' => 'brand',
            'name'             => ['en' => 'Haircut', 'ar' => 'قص شعر'],
            'description'      => ['en' => 'Classic haircut', 'ar' => 'قص شعر كلاسيكي'],
            'price'            => 25000,
            'currency_id'      => $syp->id,
            'duration'         => 30,
            'at_home'          => false,
            'active'           => true,
        ]);

        OfferedServiceModel::create([
            'serviceable_id'   => $brand->id,
            'serviceable_type' => 'brand',
            'name'             => ['en' => 'Beard Trim', 'ar' => 'تهذيب لحية'],
            'description'      => ['en' => 'Beard shaping and trim', 'ar' => 'تشذيب وتشكيل اللحية'],
            'price'            => 15000,
            'currency_id'      => $syp->id,
            'duration'         => 20,
            'at_home'          => false,
            'active'           => true,
        ]);
    }
}
