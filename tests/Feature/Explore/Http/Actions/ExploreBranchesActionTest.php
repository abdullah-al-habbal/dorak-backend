<?php

declare(strict_types=1);

use Modules\Branch\Models\BranchModel;
use Modules\Brand\Models\BrandModel;

beforeEach(function () {
    $this->brand = BrandModel::factory()->create(['universe' => 'men']);
});

it('lists branches within radius with distance', function () {
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
        'latitude' => 35.0,
        'longitude' => 38.0,
        'brand_id' => $this->brand->id,
    ]);

    $response = $this->getJson('/api/v1/explore/branches?latitude=33.5&longitude=36.3&radius=10&universe=men&per_page=10');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(0);
});

it('excludes branches with null coordinates', function () {
    BranchModel::factory()->create([
        'latitude' => null,
        'longitude' => null,
        'brand_id' => $this->brand->id,
    ]);

    $response = $this->getJson('/api/v1/explore/branches?latitude=33.5&longitude=36.3&radius=1000&universe=men&per_page=10');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(0);
});

it('orders branches by distance ascending', function () {
    $close = BranchModel::factory()->create([
        'latitude' => 33.51,
        'longitude' => 36.31,
        'brand_id' => $this->brand->id,
    ]);
    $far = BranchModel::factory()->create([
        'latitude' => 33.55,
        'longitude' => 36.35,
        'brand_id' => $this->brand->id,
    ]);

    $response = $this->getJson('/api/v1/explore/branches?latitude=33.5&longitude=36.3&radius=1000&universe=men&per_page=10');

    expect($response->json('data.0.id'))->toBe($close->id);
    expect($response->json('data.1.id'))->toBe($far->id);
    expect($response->json('data.0.distance'))->toBeLessThan($response->json('data.1.distance'));
});

it('filters branches by universe', function () {
    $women = BrandModel::factory()->create(['universe' => 'women']);
    BranchModel::factory()->create([
        'latitude' => 33.5,
        'longitude' => 36.3,
        'brand_id' => $women->id,
    ]);

    $response = $this->getJson('/api/v1/explore/branches?latitude=33.5&longitude=36.3&radius=1000&universe=men&per_page=10');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(0);
});

it('paginates branch results', function () {
    BranchModel::factory()->count(25)->create([
        'latitude' => 33.5,
        'longitude' => 36.3,
        'brand_id' => $this->brand->id,
    ]);

    $response = $this->getJson('/api/v1/explore/branches?latitude=33.5&longitude=36.3&radius=1000&universe=men&per_page=10');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(10);
    expect($response->json('meta.pagination.per_page'))->toBe(10);
    expect($response->json('meta.pagination.total'))->toBe(25);
});

it('returns empty when no branches nearby', function () {
    BranchModel::factory()->create([
        'latitude' => 90.0,
        'longitude' => 90.0,
        'brand_id' => $this->brand->id,
    ]);

    $response = $this->getJson('/api/v1/explore/branches?latitude=33.5&longitude=36.3&radius=1&universe=men&per_page=10');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(0);
});
