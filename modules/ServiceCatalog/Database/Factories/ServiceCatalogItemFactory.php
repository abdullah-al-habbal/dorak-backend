<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\ServiceCatalog\Models\ServiceCatalogCategoryModel;
use Modules\ServiceCatalog\Models\ServiceCatalogItemModel;

class ServiceCatalogItemFactory extends Factory
{
    protected $model = ServiceCatalogItemModel::class;

    public function definition(): array
    {
        $name = fake()->unique()->word();

        return [
            'category_id' => ServiceCatalogCategoryModel::factory(),
            'name' => [
                'en' => $name,
                'ar' => fake('ar_SA')->word(),
            ],
            'slug' => \Str::slug($name),
            'is_active' => true,
        ];
    }
}
