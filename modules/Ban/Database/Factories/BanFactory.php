<?php

// modules/Ban/Database/Factories/BanFactory.php
declare(strict_types=1);

namespace Modules\Ban\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Admin\Models\AdminModel;
use Modules\Ban\Models\BanModel;

class BanFactory extends Factory
{
    protected $model = BanModel::class;

    public function definition(): array
    {
        return [
            'bannable_id' => AdminModel::factory(),
            'bannable_type' => 'admin',
            'reason' => fake()->sentence(),
            'banned_from' => now()->subDay(),
        ];
    }

    public function permanent(): static
    {
        return $this->state(fn (array $attrs) => ['banned_until' => null]);
    }

    public function temporary(): static
    {
        return $this->state(fn (array $attrs) => [
            'banned_until' => now()->addDays(7),
        ]);
    }
}
