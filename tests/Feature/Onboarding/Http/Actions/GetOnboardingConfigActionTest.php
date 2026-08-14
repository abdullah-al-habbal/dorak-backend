<?php

declare(strict_types=1);

use Modules\Onboarding\Models\OnboardingConfigurationModel;

function seedOnboardingRow(array $overrides = []): void
{
    OnboardingConfigurationModel::create(array_merge([
        'locale' => 'en',
        'season' => null,
        'hero_image_path' => 'assets/images/onboarding/en/default/hero.jpg',
        'is_active' => true,
        'sort_order' => 0,
    ], $overrides));
}

it('returns the seasonal config for the requested locale', function () {
    seedOnboardingRow(['season' => 'summer', 'hero_image_path' => 'assets/images/onboarding/en/summer/hero.jpg', 'sort_order' => 1]);
    seedOnboardingRow();

    $response = $this->getJson('/api/v1/app/onboarding-config?locale=en&season=summer');

    $response->assertOk();
    $response->assertJsonStructure([
        'success', 'statusCode', 'code', 'message', 'timestamp',
        'data' => ['hero_image_url', 'season', 'locale'],
    ]);
    expect($response->json('data.season'))->toBe('summer');
    expect($response->json('data.locale'))->toBe('en');
});

it('falls back to the default row when season has no match', function () {
    seedOnboardingRow();

    $response = $this->getJson('/api/v1/app/onboarding-config?locale=en&season=winter');

    $response->assertOk();
    expect($response->json('data.season'))->toBeNull();
});

it('falls back to any active locale when requested locale has no rows', function () {
    seedOnboardingRow(['locale' => 'ar', 'hero_image_path' => 'assets/images/onboarding/ar/default/hero.jpg']);

    $response = $this->getJson('/api/v1/app/onboarding-config?locale=en');

    $response->assertOk();
    expect($response->json('data.locale'))->toBe('ar');
});

it('resolves locale from Accept-Language header', function () {
    seedOnboardingRow(['locale' => 'ar', 'hero_image_path' => 'assets/images/onboarding/ar/default/hero.jpg']);

    $response = $this->withHeaders(['Accept-Language' => 'ar'])->getJson('/api/v1/app/onboarding-config');

    $response->assertOk();
    expect($response->json('data.locale'))->toBe('ar');
});

it('returns 404 when no active config exists', function () {
    $response = $this->getJson('/api/v1/app/onboarding-config?locale=en');

    $response->assertNotFound();
});

it('validates locale and season against allowed enums', function () {
    $response = $this->getJson('/api/v1/app/onboarding-config?locale=fr&season=xyz');

    $response->assertStatus(422);
});
