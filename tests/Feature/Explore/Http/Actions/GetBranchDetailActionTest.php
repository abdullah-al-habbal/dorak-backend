<?php

declare(strict_types=1);

use Modules\Branch\Models\BranchModel;

it('shows branch detail with chairs and barbers', function () {
    $branch = BranchModel::factory()->create();

    $response = $this->getJson("/api/v1/explore/branches/{$branch->id}");

    $response->assertOk();
    $response->assertJsonStructure([
        'success',
        'data' => ['id', 'name', 'chairs_count', 'barbers', 'services'],
    ]);
    expect($response->json('data.id'))->toBe($branch->id);
});

it('returns 404 for non-existent branch', function () {
    $response = $this->getJson('/api/v1/explore/branches/00000000-0000-0000-0000-000000000000');

    $response->assertNotFound();
});
