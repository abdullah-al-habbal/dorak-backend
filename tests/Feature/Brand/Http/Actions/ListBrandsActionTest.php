<?php

declare(strict_types=1);

use Modules\Brand\Models\BrandModel;

it('lists brands', function () {
    BrandModel::factory()->count(3)->create();

    $response = $this->getJson('/api/v1/brands');

    $response->assertOk();
    $response->assertJsonStructure([
        'success',
        'data' => [
            '*' => ['id', 'name', 'logo', 'created_at'],
        ],
        'meta' => ['pagination'],
    ]);
});

it('lists brands returns empty when none exist', function () {
    $response = $this->getJson('/api/v1/brands');

    $response->assertOk();
    $response->assertJsonCount(0, 'data');
});

it('paginates brands', function () {
    BrandModel::factory()->count(25)->create();

    $response = $this->getJson('/api/v1/brands?per_page=10');

    $response->assertOk();
    expect($response->json('meta.pagination.current_page'))->toBe(1);
    expect($response->json('meta.pagination.per_page'))->toBe(10);
});
