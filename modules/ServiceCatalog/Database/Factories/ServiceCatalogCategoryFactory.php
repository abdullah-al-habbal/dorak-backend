<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\ServiceCatalog\Models\ServiceCatalogCategoryModel;

class ServiceCatalogCategoryFactory extends Factory
{
    protected $model = ServiceCatalogCategoryModel::class;

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

    public function inactive(): static
    {
        return $this->state(fn (array $attrs) => [
            'is_active' => false,
        ]);
    }
}
