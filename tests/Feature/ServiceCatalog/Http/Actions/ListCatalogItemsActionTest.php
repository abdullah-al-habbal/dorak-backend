<?php

declare(strict_types=1);

use Modules\ServiceCatalog\Models\ServiceCatalogCategoryModel;
use Modules\ServiceCatalog\Models\ServiceCatalogItemModel;

beforeEach(function () {
    $this->category = ServiceCatalogCategoryModel::factory()->create();
});

it('lists catalog items', function () {
    ServiceCatalogItemModel::factory()->count(3)->create([
        'category_id' => $this->category->id,
    ]);

    $response = $this->getJson('/api/v1/service-catalog/items');

    $response->assertOk();
    $response->assertJsonStructure([
        'success',
        'data' => ['*' => ['id', 'name', 'slug', 'category_id']],
        'meta' => ['pagination'],
    ]);
    expect($response->json('data'))->toHaveCount(3);
});

it('filters items by category', function () {
    $otherCategory = ServiceCatalogCategoryModel::factory()->create();
    ServiceCatalogItemModel::factory()->count(2)->create(['category_id' => $this->category->id]);
    ServiceCatalogItemModel::factory()->create(['category_id' => $otherCategory->id]);

    $response = $this->getJson('/api/v1/service-catalog/items?category_id='.$this->category->id);

    expect($response->json('data'))->toHaveCount(2);
});

it('searches items by name', function () {
    ServiceCatalogItemModel::factory()->create([
        'category_id' => $this->category->id,
        'name' => ['en' => 'Beard Trim', 'ar' => 'تهذيب اللحية'],
    ]);
    ServiceCatalogItemModel::factory()->create([
        'category_id' => $this->category->id,
        'name' => ['en' => 'Haircut', 'ar' => 'قص الشعر'],
    ]);

    $response = $this->getJson('/api/v1/service-catalog/items?search=Beard');

    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('data.0.name.en'))->toBe('Beard Trim');
});
