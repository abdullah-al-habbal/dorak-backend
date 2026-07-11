<?php

declare(strict_types=1);

namespace Modules\Marketing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Marketing\Models\TestimonialModel;

final class TestimonialFactory extends Factory
{
    protected $model = TestimonialModel::class;

    public function definition(): array
    {
        return [
            'author_name' => $this->faker->name(),
            'author_title' => [
                'en' => $this->faker->randomElement(['Owner', 'Client', 'Barber']),
                'ar' => 'مستخدم',
            ],
            'quote' => [
                'en' => $this->faker->sentence(10),
                'ar' => 'نص الشهادة بالعربية',
            ],
            'avatar_url' => $this->faker->imageUrl(100, 100, 'people'),
            'rating' => $this->faker->numberBetween(3, 5),
            'display_order' => $this->faker->numberBetween(0, 10),
        ];
    }

    public function owner(): static
    {
        return $this->state(fn (array $attributes): array => [
            'author_title' => ['en' => 'Owner', 'ar' => 'مالك'],
        ]);
    }

    public function client(): static
    {
        return $this->state(fn (array $attributes): array => [
            'author_title' => ['en' => 'Client', 'ar' => 'عميل'],
        ]);
    }

    public function barber(): static
    {
        return $this->state(fn (array $attributes): array => [
            'author_title' => ['en' => 'Barber', 'ar' => 'حلاق'],
        ]);
    }
}
