<?php

declare(strict_types=1);

use Modules\Chair\Models\ChairModel;

it('lists chairs', function () {
    ChairModel::factory()->count(3)->create();

    $response = $this->getJson('/api/v1/chairs');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(3);
});

it('lists chairs filtered by branch', function () {
    $branchChair = ChairModel::factory()->create();
    ChairModel::factory()->create();

    $response = $this->getJson('/api/v1/chairs?branch_id=' . $branchChair->branch_id);

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(1);
});

it('lists chairs by branch route', function () {
    $branchChair = ChairModel::factory()->create();
    ChairModel::factory()->create();

    $response = $this->getJson("/api/v1/branches/{$branchChair->branch_id}/chairs");

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(1);
});

it('shows chair', function () {
    $chair = ChairModel::factory()->create();

    $response = $this->getJson("/api/v1/chairs/{$chair->id}");

    $response->assertOk();
    expect($response->json('data.id'))->toBe($chair->id);
});

it('returns 404 for non-existent chair', function () {
    $response = $this->getJson('/api/v1/chairs/non-existent-id');

    $response->assertNotFound();
});
