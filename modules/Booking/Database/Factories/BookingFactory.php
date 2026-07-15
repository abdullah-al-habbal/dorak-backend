<?php

// modules/Booking/Database/Factories/BookingFactory.php
declare(strict_types=1);

namespace Modules\Booking\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Barber\Models\BarberModel;
use Modules\Booking\Models\BookingModel;
use Modules\Chair\Models\ChairModel;
use Modules\Client\Models\ClientModel;

class BookingFactory extends Factory
{
    protected $model = BookingModel::class;

    public function definition(): array
    {
        return [
            'client_id' => ClientModel::factory(),
            'chair_id' => ChairModel::factory(),
            'barber_id' => BarberModel::factory(),
            'time_slot' => fake()->dateTimeBetween('+1 day', '+1 week'),
            'status' => 'confirmed',
            'at_home_location' => null,
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attrs) => [
            'status' => 'completed',
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attrs) => [
            'status' => 'canceled',
        ]);
    }

    public function atHome(): static
    {
        return $this->state(fn (array $attrs) => [
            'at_home_location' => [
                'lat' => fake()->latitude(),
                'lng' => fake()->longitude(),
                'address' => fake()->address(),
            ],
            'chair_id' => null,
        ]);
    }
}
