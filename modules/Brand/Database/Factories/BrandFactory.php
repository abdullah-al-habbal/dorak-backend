<?php

// modules/Brand/Database/Factories/BrandFactory.php
declare(strict_types=1);

namespace Modules\Brand\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Brand\Models\BrandModel;
use Modules\Client\Models\ClientModel;
use Modules\Currency\Models\CurrencyModel;

class BrandFactory extends Factory
{
    protected $model = BrandModel::class;

    public function definition(): array
    {
        return [
            'owner_id' => ClientModel::factory(),
            'name' => [
                'en' => fake()->company(),
                'ar' => fake('ar_SA')->company(),
            ],
            'description' => [
                'en' => fake()->sentence(),
                'ar' => fake('ar_SA')->sentence(),
            ],
            'logo' => null,
            'base_currency_id' => CurrencyModel::factory(),
        ];
    }
}
