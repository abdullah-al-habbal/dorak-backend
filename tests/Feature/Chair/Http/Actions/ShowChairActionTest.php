<?php

declare(strict_types=1);

use Modules\Chair\Models\ChairModel;

it('shows a chair', function () {
    $chair = ChairModel::factory()->create();

    $response = $this->getJson("/api/v1/chairs/{$chair->id}");

    $response->assertOk();
    $response->assertJsonStructure([
        'success',
        'data' => ['id', 'label', 'status', 'ui_metadata', 'branch_id', 'created_at'],
    ]);
    expect($response->json('data.id'))->toBe($chair->id);
});

it('returns 404 for non-existent chair', function () {
    $response = $this->getJson('/api/v1/chairs/non-existent-id');

    $response->assertNotFound();
});
