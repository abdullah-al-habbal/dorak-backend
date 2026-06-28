<?php
// modules/Language/Database/Factories/LanguageFactory.php
declare(strict_types=1);

namespace Modules\Language\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Language\Models\LanguageModel;

class LanguageFactory extends Factory
{
    protected $model = LanguageModel::class;

    public function definition(): array
    {
        return [
            'code'       => fake()->unique()->randomElement(['en', 'ar']),
            'name'       => [
                'en' => fake()->word(),
                'ar' => fake('ar_SA')->word(),
            ],
            'direction'  => 'ltr',
            'is_default' => false,
        ];
    }
}
