<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Barber\Models\BarberModel;
use Modules\BarberAffiliation\Enums\AffiliationStatus;
use Modules\BarberAffiliation\Enums\AffiliableType;
use Modules\BarberAffiliation\Models\BarberAffiliationModel;
use Modules\Branch\Models\BranchModel;

class BarberAffiliationFactory extends Factory
{
    protected $model = BarberAffiliationModel::class;

    public function definition(): array
    {
        return [
            'barber_id' => BarberModel::factory(),
            'affiliable_id' => BranchModel::factory(),
            'affiliable_type' => AffiliableType::Branch->value,
            'status' => AffiliationStatus::Accepted->value,
            'commission_rate' => null,
            'invited_at' => now()->subDays(7),
            'accepted_at' => now()->subDays(6),
            'terminated_at' => null,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attrs) => [
            'status' => AffiliationStatus::Pending->value,
            'accepted_at' => null,
        ]);
    }

    public function terminated(): static
    {
        return $this->state(fn (array $attrs) => [
            'status' => AffiliationStatus::Terminated->value,
            'terminated_at' => now(),
        ]);
    }
}
