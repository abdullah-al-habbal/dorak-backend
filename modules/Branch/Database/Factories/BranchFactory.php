<?php
// modules/Branch/Database/Factories/BranchFactory.php

declare(strict_types=1);

namespace Modules\Branch\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Branch\Models\BranchModel;

class BranchFactory extends Factory
{
    protected $model = BranchModel::class;

    public function definition(): array
    {
        return [
            'name'              => [
                'en' => fake()->name(),
                'ar' => fake('ar_SA')->name(),
            ],
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => Hash::make('password'),
            'remember_token'    => Str::random(10),
            'status'            => 'pending',
        ];
    }
}
