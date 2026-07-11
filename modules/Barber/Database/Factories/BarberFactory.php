<?php

// modules/Barber/Database/Factories/BarberFactory.php

declare(strict_types=1);

namespace Modules\Barber\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Barber\Models\BarberModel;

class BarberFactory extends Factory
{
    protected $model = BarberModel::class;

    public function definition(): array
    {
        return [
            'name' => [
                'en' => fake()->name(),
                'ar' => fake('ar_SA')->name(),
            ],
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'is_freelancer' => false,
            'status' => 'pending',
            'client_id' => null,
        ];
    }

    public function freelancer(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_freelancer' => true,
        ]);
    }
}
