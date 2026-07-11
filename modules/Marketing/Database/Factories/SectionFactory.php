<?php

declare(strict_types=1);

namespace Modules\Marketing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Marketing\Models\SectionModel;

final class SectionFactory extends Factory
{
    protected $model = SectionModel::class;

    public function definition(): array
    {
        return [
            'type' => 'hero',
            'title' => [
                'en' => $this->faker->sentence(3),
                'ar' => 'عنوان القسم',
            ],
            'subtitle' => [
                'en' => $this->faker->sentence(5),
                'ar' => 'نص فرعي',
            ],
            'content' => [
                'en' => [
                    'heading' => $this->faker->sentence(4),
                    'subheading' => $this->faker->sentence(8),
                    'cta_text' => 'Get Started',
                    'cta_url' => '/register',
                    'image' => $this->faker->imageUrl(),
                ],
                'ar' => [
                    'heading' => 'عنوان القسم',
                    'subheading' => 'نص فرعي للقسم',
                    'cta_text' => 'ابدأ الآن',
                    'cta_url' => '/register',
                    'image' => $this->faker->imageUrl(),
                ],
            ],
            'media_url' => $this->faker->imageUrl(),
            'sort_order' => 0,
            'universe_visibility' => 'all',
        ];
    }

    public function hero(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => 'hero',
            'sort_order' => 0,
        ]);
    }

    public function featureList(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => 'feature_list',
            'sort_order' => 1,
        ]);
    }

    public function testimonials(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => 'testimonials',
            'sort_order' => 2,
        ]);
    }

    public function floorPlanDemo(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => 'floor_plan_demo',
            'sort_order' => 3,
        ]);
    }

    public function pricing(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => 'pricing',
            'sort_order' => 4,
        ]);
    }

    public function cta(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => 'cta',
            'sort_order' => 5,
        ]);
    }
}
