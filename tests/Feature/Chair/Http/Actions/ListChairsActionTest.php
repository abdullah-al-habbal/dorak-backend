<?php

declare(strict_types=1);

use Modules\Chair\Models\ChairModel;

it('lists chairs', function () {
    ChairModel::factory()->count(3)->create();

    $response = $this->getJson('/api/v1/chairs');

    $response->assertOk();
    $response->assertJsonStructure([
        'success',
        'data' => [
            '*' => ['id', 'label', 'status', 'ui_metadata', 'branch_id', 'created_at'],
        ],
        'meta' => ['pagination'],
    ]);
});

it('filters chairs by branch_id', function () {
    $chairs = ChairModel::factory()->count(3)->create();
    $target = $chairs->first();

    $response = $this->getJson("/api/v1/chairs?branch_id={$target->branch_id}");

    $response->assertOk();
    foreach ($response->json('data') as $chair) {
        expect($chair['branch_id'])->toBe($target->branch_id);
    }
});

it('filters chairs by status', function () {
    ChairModel::factory()->maintenance()->create();
    ChairModel::factory()->count(2)->create();

    $response = $this->getJson('/api/v1/chairs?status=maintenance');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('data.0.status'))->toBe('maintenance');
});
