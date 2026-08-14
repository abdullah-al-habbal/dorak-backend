<?php

declare(strict_types=1);

return [
    // English default
    [
        'locale' => 'en',
        'season' => null,
        'source_path' => database_path('seeders/assets/onboarding/hero_default_en.jpg'),
        'is_active' => true,
        'sort_order' => 0,
    ],
    // Arabic default
    [
        'locale' => 'ar',
        'season' => null,
        'source_path' => database_path('seeders/assets/onboarding/hero_default_ar.jpg'),
        'is_active' => true,
        'sort_order' => 0,
    ],
    // English seasonal
    [
        'locale' => 'en',
        'season' => 'spring',
        'source_path' => database_path('seeders/assets/onboarding/hero_spring_en.jpg'),
        'is_active' => true,
        'sort_order' => 1,
    ],
    [
        'locale' => 'en',
        'season' => 'summer',
        'source_path' => database_path('seeders/assets/onboarding/hero_summer_en.jpg'),
        'is_active' => true,
        'sort_order' => 1,
    ],
    [
        'locale' => 'en',
        'season' => 'autumn',
        'source_path' => database_path('seeders/assets/onboarding/hero_autumn_en.jpg'),
        'is_active' => true,
        'sort_order' => 1,
    ],
    [
        'locale' => 'en',
        'season' => 'winter',
        'source_path' => database_path('seeders/assets/onboarding/hero_winter_en.jpg'),
        'is_active' => true,
        'sort_order' => 1,
    ],
    // Arabic seasonal
    [
        'locale' => 'ar',
        'season' => 'spring',
        'source_path' => database_path('seeders/assets/onboarding/hero_spring_ar.jpg'),
        'is_active' => true,
        'sort_order' => 1,
    ],
    [
        'locale' => 'ar',
        'season' => 'summer',
        'source_path' => database_path('seeders/assets/onboarding/hero_summer_ar.jpg'),
        'is_active' => true,
        'sort_order' => 1,
    ],
    [
        'locale' => 'ar',
        'season' => 'autumn',
        'source_path' => database_path('seeders/assets/onboarding/hero_autumn_ar.jpg'),
        'is_active' => true,
        'sort_order' => 1,
    ],
    [
        'locale' => 'ar',
        'season' => 'winter',
        'source_path' => database_path('seeders/assets/onboarding/hero_winter_ar.jpg'),
        'is_active' => true,
        'sort_order' => 1,
    ],
];
