<?php

declare(strict_types=1);

use Modules\Barber\Models\BarberModel;

it('lists freelancer barbers within radius with distance', function () {
    $barber = BarberModel::factory()->freelancer()->create([
        'latitude' => 33.5,
        'longitude' => 36.3,
    ]);

    $response = $this->getJson('/api/v1/explore/barbers?lat=33.5&lng=36.3&radius=1000');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('data.0.id'))->toBe($barber->id);
    expect($response->json('data.0.distance'))->toBeLessThan(0.1);
});

it('excludes non-freelancer barbers', function () {
    BarberModel::factory()->create([
        'is_freelancer' => false,
        'latitude' => 33.5,
        'longitude' => 36.3,
    ]);

    $response = $this->getJson('/api/v1/explore/barbers?lat=33.5&lng=36.3&radius=1000');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(0);
});

it('excludes barbers outside radius', function () {
    BarberModel::factory()->freelancer()->create([
        'latitude' => 35.0,
        'longitude' => 38.0,
    ]);

    $response = $this->getJson('/api/v1/explore/barbers?lat=33.5&lng=36.3&radius=10');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(0);
});

it('excludes barbers with null coordinates', function () {
    BarberModel::factory()->freelancer()->create([
        'latitude' => null,
        'longitude' => null,
    ]);

    $response = $this->getJson('/api/v1/explore/barbers?lat=33.5&lng=36.3&radius=1000');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(0);
});

it('orders freelancer barbers by distance ascending', function () {
    $close = BarberModel::factory()->freelancer()->create([
        'latitude' => 33.51,
        'longitude' => 36.31,
    ]);
    $far = BarberModel::factory()->freelancer()->create([
        'latitude' => 33.55,
        'longitude' => 36.35,
    ]);

    $response = $this->getJson('/api/v1/explore/barbers?lat=33.5&lng=36.3&radius=1000');

    expect($response->json('data.0.id'))->toBe($close->id);
    expect($response->json('data.1.id'))->toBe($far->id);
    expect($response->json('data.0.distance'))->toBeLessThan($response->json('data.1.distance'));
});

it('paginates barber results', function () {
    BarberModel::factory()->count(25)->freelancer()->create([
        'latitude' => 33.5,
        'longitude' => 36.3,
    ]);

    $response = $this->getJson('/api/v1/explore/barbers?lat=33.5&lng=36.3&radius=1000&per_page=10');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(10);
    expect($response->json('meta.pagination.per_page'))->toBe(10);
    expect($response->json('meta.pagination.total'))->toBe(25);
});

it('returns empty when no barbers nearby', function () {
    BarberModel::factory()->freelancer()->create([
        'latitude' => 90.0,
        'longitude' => 90.0,
    ]);

    $response = $this->getJson('/api/v1/explore/barbers?lat=33.5&lng=36.3&radius=1');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(0);
});
