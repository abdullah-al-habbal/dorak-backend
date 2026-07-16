<?php

// modules/JobPosting/Database/Factories/JobPostingFactory.php
declare(strict_types=1);

namespace Modules\JobPosting\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Branch\Models\BranchModel;
use Modules\JobPosting\Models\JobPostingModel;

class JobPostingFactory extends Factory
{
    protected $model = JobPostingModel::class;

    public function definition(): array
    {
        return [
            'branch_id' => BranchModel::factory(),
            'title' => [
                'en' => fake()->jobTitle(),
                'ar' => fake('ar_SA')->jobTitle(),
            ],
            'description' => [
                'en' => fake()->paragraph(),
                'ar' => fake('ar_SA')->paragraph(),
            ],
            'status' => 'open',
            'requirements' => [
                'en' => fake()->sentence(),
                'ar' => fake('ar_SA')->sentence(),
            ],
            'location' => fake()->city(),
            'type' => fake()->randomElement(['full-time', 'part-time', 'contract']),
        ];
    }
}
