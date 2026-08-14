<?php

declare(strict_types=1);

namespace Modules\Chair\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Branch\Models\BranchModel;
use Modules\Chair\Enums\ChairStatus;
use Modules\Chair\Models\ChairModel;

class ChairFactory extends Factory
{
    protected $model = ChairModel::class;

    private static int $labelSequence = 0;

    public function definition(): array
    {
        return [
            'branch_id' => BranchModel::factory(),
            'barber_id' => null,
            'label' => (string) (++self::$labelSequence),
            'ui_metadata' => [
                'shape' => 'rectangle',
                'position_x' => fake()->numberBetween(10, 500),
                'position_y' => fake()->numberBetween(10, 500),
                'width' => 60,
                'height' => 60,
                'rotation' => 0,
            ],
            'status' => ChairStatus::Available->value,
        ];
    }

    public function occupied(): static
    {
        return $this->state(fn (array $attrs) => [
            'status' => ChairStatus::Occupied->value,
        ]);
    }

    public function maintenance(): static
    {
        return $this->state(fn (array $attrs) => [
            'status' => ChairStatus::Maintenance->value,
        ]);
    }
}
