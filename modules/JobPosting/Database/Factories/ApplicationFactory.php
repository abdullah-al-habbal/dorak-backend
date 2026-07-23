<?php

declare(strict_types=1);

namespace Modules\JobPosting\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Barber\Models\BarberModel;
use Modules\JobPosting\Enums\ApplicationStatus;
use Modules\JobPosting\Models\ApplicationModel;
use Modules\JobPosting\Models\JobPostingModel;

class ApplicationFactory extends Factory
{
    protected $model = ApplicationModel::class;

    public function definition(): array
    {
        return [
            'job_posting_id' => JobPostingModel::factory(),
            'barber_id' => BarberModel::factory(),
            'profile_snapshot' => [
                'name' => fake()->name(),
                'bio' => fake()->sentence(),
                'is_freelancer' => false,
                'rating' => fake()->randomFloat(1, 3, 5),
            ],
            'status' => ApplicationStatus::Submitted->value,
        ];
    }
}
