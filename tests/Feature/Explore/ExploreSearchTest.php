<?php

declare(strict_types=1);

use Modules\Branch\Models\BranchModel;
use Modules\Brand\Models\BrandModel;

beforeEach(function () {
    $this->brand = BrandModel::factory()->create(['universe' => 'men']);
});

it('returns branches within Haversine radius', function () {
    $branch = BranchModel::factory()->create([
        'latitude' => 33.5,
        'longitude' => 36.3,
        'brand_id' => $this->brand->id,
    ]);

    $response = $this->getJson('/api/v1/explore/branches?latitude=33.5&longitude=36.3&radius=1000&universe=men&per_page=10');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('data.0.id'))->toBe($branch->id);
    expect($response->json('data.0.distance'))->toBeLessThan(0.1);
});

it('excludes branches outside radius', function () {
    BranchModel::factory()->create([
        'latitude' => 90.0,
        'longitude' => 90.0,
        'brand_id' => $this->brand->id,
    ]);

    $response = $this->getJson('/api/v1/explore/branches?latitude=33.5&longitude=36.3&radius=1&universe=men&per_page=10');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(0);
});

it('filters by universe and excludes mismatched', function () {
    $womenBrand = BrandModel::factory()->create(['universe' => 'women']);
    BranchModel::factory()->create([
        'latitude' => 33.5,
        'longitude' => 36.3,
        'brand_id' => $womenBrand->id,
    ]);

    $response = $this->getJson('/api/v1/explore/branches?latitude=33.5&longitude=36.3&radius=1000&universe=men&per_page=10');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(0);
});

it('paginates search results', function () {
    BranchModel::factory()->count(25)->create([
        'latitude' => 33.5,
        'longitude' => 36.3,
        'brand_id' => $this->brand->id,
    ]);

    $response = $this->getJson('/api/v1/explore/branches?latitude=33.5&longitude=36.3&radius=1000&universe=men&per_page=10');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(10);
    expect($response->json('meta.pagination.total'))->toBe(25);
    expect($response->json('meta.pagination.total_pages'))->toBe(3);
});

it('returns empty for completely out-of-range', function () {
    BranchModel::factory()->create([
        'latitude' => 90.0,
        'longitude' => 90.0,
        'brand_id' => $this->brand->id,
    ]);

    $response = $this->getJson('/api/v1/explore/branches?latitude=33.5&longitude=36.3&radius=1&universe=men&per_page=10');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(0);
    expect($response->json('meta.pagination.total'))->toBe(0);
});
