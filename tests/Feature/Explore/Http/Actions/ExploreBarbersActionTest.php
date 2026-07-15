<?php

declare(strict_types=1);

use Modules\Barber\Models\BarberModel;

it('lists freelancer barbers within radius', function () {
    BarberModel::factory()->count(3)->create([
        'is_freelancer' => true,
    ]);

    $response = $this->getJson('/api/v1/explore/barbers?lat=33.5&lng=36.3&radius=1000');

    $response->assertOk();
    $response->assertJsonStructure([
        'success',
        'data' => ['*' => ['id', 'name', 'distance']],
        'meta' => ['pagination'],
    ]);
});

it('does not include non-freelancer barbers', function () {
    BarberModel::factory()->create([
        'is_freelancer' => false,
    ]);

    $response = $this->getJson('/api/v1/explore/barbers?lat=33.5&lng=36.3&radius=1000');

    expect($response->json('data'))->toHaveCount(0);
});

it('returns empty when no barbers nearby', function () {
    BarberModel::factory()->create([
        'is_freelancer' => true,
        'latitude' => 90.0,
        'longitude' => 90.0,
    ]);

    $response = $this->getJson('/api/v1/explore/barbers?lat=33.5&lng=36.3&radius=1');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(0);
});
