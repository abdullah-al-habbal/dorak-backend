<?php

// modules/Activation/Database/Factories/ActivationLogFactory.php
declare(strict_types=1);

namespace Modules\Activation\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Activation\Models\ActivationLogModel;
use Modules\Admin\Models\AdminModel;
use Modules\Barber\Models\BarberModel;

class ActivationLogFactory extends Factory
{
    protected $model = ActivationLogModel::class;

    public function definition(): array
    {
        return [
            'activable_id' => BarberModel::factory(),
            'activable_type' => BarberModel::class,
            'status' => 'pending',
            'admin_id' => AdminModel::factory(),
            'activated_at' => now(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attrs) => ['status' => 'pending']);
    }

    public function enabled(): static
    {
        return $this->state(fn (array $attrs) => ['status' => 'enabled']);
    }

    public function disabled(): static
    {
        return $this->state(fn (array $attrs) => ['status' => 'disabled']);
    }
}
