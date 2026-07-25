<?php

declare(strict_types=1);

namespace Modules\Barber\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Barber\Models\BarberPortfolioPhotoModel;

class BarberPortfolioPhotoFactory extends Factory
{
    protected $model = BarberPortfolioPhotoModel::class;

    public function definition(): array
    {
        return [
            'barber_id' => null,
            'path' => 'portfolio/' . fake()->uuid() . '/photo.jpg',
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}
