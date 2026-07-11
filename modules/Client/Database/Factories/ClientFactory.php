<?php

// modules/Client/Database/Factories/ClientFactory.php

declare(strict_types=1);

namespace Modules\Client\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Client\Models\ClientModel;

class ClientFactory extends Factory
{
    protected $model = ClientModel::class;

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
        ];
    }
}
