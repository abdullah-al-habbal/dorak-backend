<?php

// modules/Review/Database/Factories/ReviewFactory.php
declare(strict_types=1);

namespace Modules\Review\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Booking\Models\BookingModel;
use Modules\Client\Models\ClientModel;
use Modules\Review\Models\ReviewModel;

class ReviewFactory extends Factory
{
    protected $model = ReviewModel::class;

    public function definition(): array
    {
        return [
            'booking_id' => BookingModel::factory()->completed(),
            'author_id' => ClientModel::factory(),
            'author_type' => 'client',
            'subject_id' => ClientModel::factory(),
            'subject_type' => 'client',
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->sentence(),
        ];
    }
}
