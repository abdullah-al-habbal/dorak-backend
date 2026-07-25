<?php

declare(strict_types=1);

use Modules\Brand\Models\BrandModel;
use Modules\Client\Models\ClientModel;
use Modules\Currency\Models\CurrencyModel;

beforeEach(function () {
    $this->client = ClientModel::factory()->create();
    $this->actingAs($this->client, 'client');
});

it('lists brands', function () {
    BrandModel::factory()->count(3)->create();

    $response = $this->getJson('/api/v1/brands');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(3);
});

it('shows brand by id', function () {
    $brand = BrandModel::factory()->create();

    $response = $this->getJson("/api/v1/brands/{$brand->id}");

    $response->assertOk();
    expect($response->json('data.id'))->toBe($brand->id);
});

it('returns 404 for non-existent brand', function () {
    $response = $this->getJson('/api/v1/brands/00000000-0000-0000-0000-000000000000');

    $response->assertNotFound();
});

it('creates brand with translatable name', function () {
    $currency = CurrencyModel::factory()->create();

    $response = $this->postJson('/api/v1/brands', [
        'name' => ['en' => 'New Brand', 'ar' => 'ماركة جديدة'],
        'base_currency_id' => $currency->id,
    ]);

    $response->assertCreated();
    expect($response->json('data.name.en'))->toBe('New Brand');
    expect($response->json('data.name.ar'))->toBe('ماركة جديدة');
});

it('updates own brand name', function () {
    $brand = BrandModel::factory()->create(['owner_id' => $this->client->id]);

    $response = $this->putJson("/api/v1/brands/{$brand->id}", [
        'name' => ['en' => 'Updated Name'],
    ]);

    $response->assertOk();
    expect($response->json('data.name.en'))->toBe('Updated Name');
});

it('rejects updating another users brand', function () {
    $other = ClientModel::factory()->create();
    $brand = BrandModel::factory()->create(['owner_id' => $other->id]);

    $response = $this->putJson("/api/v1/brands/{$brand->id}", [
        'name' => ['en' => 'Hacked'],
    ]);

    $response->assertForbidden();
});
