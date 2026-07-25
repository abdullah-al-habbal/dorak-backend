<?php

declare(strict_types=1);

use Modules\Barber\Models\BarberModel;

beforeEach(function () {
    $this->barber = BarberModel::factory()->create();
    $this->actingAs($this->barber, 'barber');
});

it('updates travel radius and location', function () {
    $response = $this->patchJson('/api/v1/barber/travel-radius', [
        'travel_radius' => 15.5,
        'latitude' => 24.7136,
        'longitude' => 46.6753,
    ]);

    $response->assertOk();
    expect($response->json('data.travel_radius'))->toBe('15.50');
    expect($response->json('data.latitude'))->toBe('24.71360000');
    expect($response->json('data.longitude'))->toBe('46.67530000');
});

it('partially updates travel radius', function () {
    $response = $this->patchJson('/api/v1/barber/travel-radius', [
        'travel_radius' => 10.0,
    ]);

    $response->assertOk();
    expect($response->json('data.travel_radius'))->toBe('10.00');
});

it('validates latitude range', function () {
    $response = $this->patchJson('/api/v1/barber/travel-radius', [
        'latitude' => 100,
    ]);

    $response->assertStatus(422);
});

it('validates longitude range', function () {
    $response = $this->patchJson('/api/v1/barber/travel-radius', [
        'longitude' => 200,
    ]);

    $response->assertStatus(422);
});

it('requires authentication for travel radius update', function () {
    $this->app->get('auth')->forgetGuards();

    $response = $this->patchJson('/api/v1/barber/travel-radius', [
        'travel_radius' => 10.0,
    ]);

    $response->assertUnauthorized();
});
