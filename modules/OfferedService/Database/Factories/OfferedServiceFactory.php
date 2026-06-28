<?php
// modules/OfferedService/Database/Factories/OfferedServiceFactory.php
declare(strict_types=1);

namespace Modules\OfferedService\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Brand\Models\BrandModel;
use Modules\Currency\Models\CurrencyModel;
use Modules\OfferedService\Models\OfferedServiceModel;

class OfferedServiceFactory extends Factory
{
    protected $model = OfferedServiceModel::class;

    public function definition(): array
    {
        return [
            'serviceable_id'    => BrandModel::factory(),
            'serviceable_type'  => 'brand',
            'name'              => [
                'en' => fake()->word(),
                'ar' => fake('ar_SA')->word(),
            ],
            'description'       => [
                'en' => fake()->sentence(),
                'ar' => fake('ar_SA')->sentence(),
            ],
            'price'             => fake()->randomFloat(2, 10, 500),
            'currency_id'       => CurrencyModel::factory(),
            'duration'          => fake()->randomElement([15, 30, 45, 60]),
            'at_home'           => false,
            'active'            => true,
        ];
    }

    public function atHome(): static
    {
        return $this->state(fn (array $attrs) => [
            'at_home' => true,
        ]);
    }

    public function branchService(): static
    {
        return $this->state(fn (array $attrs) => [
            'serviceable_type' => 'branch',
        ]);
    }
}
