<?php

declare(strict_types=1);

use Modules\Brand\Models\BrandModel;
use Modules\Client\Models\ClientModel;
use Modules\Currency\Models\CurrencyModel;

beforeEach(function () {
    $this->client = ClientModel::factory()->create();
    $this->actingAs($this->client, 'client');
});

it('updates own brand with valid data', function () {
    $brand = BrandModel::factory()->create(['owner_id' => $this->client->id]);

    $response = $this->putJson("/api/v1/brands/{$brand->id}", [
        'name' => ['en' => 'Updated EN', 'ar' => 'Updated AR'],
        'description' => ['en' => 'New desc'],
        'logo' => 'https://example.com/new-logo.png',
    ]);

    $response->assertOk();
    $response->assertJsonStructure([
        'success',
        'data' => ['id', 'name', 'description', 'logo', 'owner', 'base_currency', 'created_at'],
    ]);
    expect($response->json('data.name.en'))->toBe('Updated EN');
    expect($response->json('data.name.ar'))->toBe('Updated AR');
    expect($response->json('data.logo'))->toBe('https://example.com/new-logo.png');
});

it('updates only provided fields', function () {
    $brand = BrandModel::factory()->create([
        'owner_id' => $this->client->id,
        'name' => ['en' => 'Original EN', 'ar' => 'Original AR'],
    ]);

    $response = $this->putJson("/api/v1/brands/{$brand->id}", [
        'name' => ['en' => 'Changed EN'],
    ]);

    $response->assertOk();
    expect($response->json('data.name.en'))->toBe('Changed EN');
    expect($response->json('data.name.ar'))->toBe('Original AR');
});

it('updates base_currency_id', function () {
    $brand = BrandModel::factory()->create(['owner_id' => $this->client->id]);
    $newCurrency = CurrencyModel::factory()->create();

    $response = $this->putJson("/api/v1/brands/{$brand->id}", [
        'base_currency_id' => $newCurrency->id,
    ]);

    $response->assertOk();
    expect($response->json('data.base_currency.id'))->toBe($newCurrency->id);
});

it('rejects update with invalid base_currency_id', function () {
    $brand = BrandModel::factory()->create(['owner_id' => $this->client->id]);

    $response = $this->putJson("/api/v1/brands/{$brand->id}", [
        'base_currency_id' => '00000000-0000-0000-0000-000000000000',
    ]);

    $response->assertStatus(422);
});

it('rejects update with invalid logo url', function () {
    $brand = BrandModel::factory()->create(['owner_id' => $this->client->id]);

    $response = $this->putJson("/api/v1/brands/{$brand->id}", [
        'logo' => 'not-a-url',
    ]);

    $response->assertStatus(422);
});

it('rejects updating another users brand', function () {
    $otherClient = ClientModel::factory()->create();
    $brand = BrandModel::factory()->create(['owner_id' => $otherClient->id]);

    $response = $this->putJson("/api/v1/brands/{$brand->id}", [
        'name' => ['en' => 'Hacked'],
    ]);

    $response->assertForbidden();
});

it('rejects unauthorized brand update', function () {
    $this->app->get('auth')->forgetGuards();
    $brand = BrandModel::factory()->create();

    $response = $this->putJson("/api/v1/brands/{$brand->id}", [
        'name' => ['en' => 'Hacked'],
    ]);

    $response->assertUnauthorized();
});

it('returns 404 for non-existent brand', function () {
    $response = $this->putJson('/api/v1/brands/00000000-0000-0000-0000-000000000000', [
        'name' => ['en' => 'Ghost'],
    ]);

    $response->assertNotFound();
});
