<?php

declare(strict_types=1);

use Modules\Client\Models\ClientModel;
use Modules\ServiceCatalog\Models\ServiceCatalogCategoryModel;

beforeEach(function () {
    $this->client = ClientModel::factory()->create();
    $this->actingAs($this->client, 'client');
    $this->category = ServiceCatalogCategoryModel::factory()->create();
});

it('creates a catalog item', function () {
    $payload = [
        'category_id' => $this->category->id,
        'name' => ['en' => 'Classic Haircut', 'ar' => 'حلاقة كلاسيكية'],
        'slug' => 'classic-haircut',
        'is_active' => true,
    ];

    $response = $this->postJson('/api/v1/service-catalog/items', $payload);

    $response->assertCreated();
    $response->assertJsonStructure([
        'success',
        'data' => ['id', 'name', 'slug'],
        'message',
    ]);
    expect($response->json('data.name.en'))->toBe('Classic Haircut');
});

it('requires category_id', function () {
    $response = $this->postJson('/api/v1/service-catalog/items', [
        'name' => ['en' => 'Test', 'ar' => 'اختبار'],
        'slug' => 'test',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['category_id']);
});

it('requires unique slug', function () {
    $payload = [
        'category_id' => $this->category->id,
        'name' => ['en' => 'First', 'ar' => 'الأول'],
        'slug' => 'duplicate',
        'is_active' => true,
    ];
    $this->postJson('/api/v1/service-catalog/items', $payload);

    $response = $this->postJson('/api/v1/service-catalog/items', $payload);
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['slug']);
});

it('requires authentication', function () {
    $this->app->get('auth')->forgetGuards();

    $response = $this->postJson('/api/v1/service-catalog/items', [
        'category_id' => $this->category->id,
        'name' => ['en' => 'Test', 'ar' => 'اختبار'],
        'slug' => 'test',
    ]);

    $response->assertUnauthorized();
});
