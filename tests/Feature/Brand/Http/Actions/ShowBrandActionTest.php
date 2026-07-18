<?php

declare(strict_types=1);

use Modules\Brand\Models\BrandModel;

it('shows a brand', function () {
    $brand = BrandModel::factory()->create();

    $response = $this->getJson("/api/v1/brands/{$brand->id}");

    $response->assertOk();
    $response->assertJsonStructure([
        'success',
        'data' => ['id', 'name', 'description', 'logo', 'owner', 'base_currency', 'created_at'],
    ]);
    expect($response->json('data.id'))->toBe($brand->id);
});

it('returns 404 for non-existent brand', function () {
    $response = $this->getJson('/api/v1/brands/00000000-0000-0000-0000-000000000000');

    $response->assertNotFound();
});
