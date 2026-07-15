<?php

declare(strict_types=1);

use Modules\Branch\Models\BranchModel;

it('lists branches within radius', function () {
    BranchModel::factory()->count(3)->create();

    $response = $this->getJson('/api/v1/explore/branches?lat=33.5&lng=36.3&radius=1000');

    $response->assertOk();
    $response->assertJsonStructure([
        'success',
        'data' => ['*' => ['id', 'name', 'distance']],
        'meta' => ['pagination'],
    ]);
});

it('filters branches by universe', function () {
    BranchModel::factory()->count(2)->create();

    $response = $this->getJson('/api/v1/explore/branches?lat=33.5&lng=36.3&radius=1000&universe=neutral');

    $response->assertOk();
});

it('returns empty when no branches nearby', function () {
    BranchModel::factory()->create([
        'latitude' => 90.0,
        'longitude' => 90.0,
    ]);

    $response = $this->getJson('/api/v1/explore/branches?lat=33.5&lng=36.3&radius=1');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(0);
});
