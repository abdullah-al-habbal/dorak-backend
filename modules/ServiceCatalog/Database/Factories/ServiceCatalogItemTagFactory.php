<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\ServiceCatalog\Models\ServiceCatalogItemTagModel;

class ServiceCatalogItemTagFactory extends Factory
{
    protected $model = ServiceCatalogItemTagModel::class;

    public function definition(): array
    {
        $name = fake()->unique()->word();

        return [
            'name' => [
                'en' => $name,
                'ar' => fake('ar_SA')->word(),
            ],
            'slug' => \Str::slug($name),
            'is_active' => true,
        ];
    }
}
