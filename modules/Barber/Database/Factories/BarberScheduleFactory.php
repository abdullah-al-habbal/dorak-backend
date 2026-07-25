<?php

declare(strict_types=1);

namespace Modules\Barber\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Barber\Models\BarberScheduleModel;

class BarberScheduleFactory extends Factory
{
    protected $model = BarberScheduleModel::class;

    public function definition(): array
    {
        return [
            'barber_id' => null,
            'day_of_week' => fake()->numberBetween(0, 6),
            'start_time' => '09:00',
            'end_time' => '17:00',
            'is_active' => true,
        ];
    }
}
