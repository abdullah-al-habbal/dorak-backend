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

it('shows brand', function () {
    $brand = BrandModel::factory()->create();

    $response = $this->getJson("/api/v1/brands/{$brand->id}");

    $response->assertOk();
    expect($response->json('data.id'))->toBe($brand->id);
});

it('creates brand', function () {
    $currency = CurrencyModel::factory()->create();

    $response = $this->postJson('/api/v1/brands', [
        'name' => ['en' => 'Test Brand EN', 'ar' => 'Test Brand AR'],
        'owner_id' => $this->client->id,
        'base_currency_id' => $currency->id,
    ]);

    $response->assertCreated();
    expect($response->json('data.name.en'))->toBe('Test Brand EN');
    expect($response->json('data.name.ar'))->toBe('Test Brand AR');
});

it('updates brand', function () {
    $brand = BrandModel::factory()->create();

    $response = $this->putJson("/api/v1/brands/{$brand->id}", [
        'name' => ['en' => 'Updated EN'],
    ]);

    $response->assertOk();
    expect($response->json('data.name.en'))->toBe('Updated EN');
});

it('rejects create with missing name', function () {
    $response = $this->postJson('/api/v1/brands', [
        'owner_id' => $this->client->id,
        'base_currency_id' => '00000000-0000-0000-0000-000000000000',
    ]);

    $response->assertStatus(422);
});

it('rejects unauthorized brand create', function () {
    $this->app->get('auth')->forgetGuards();
    $currency = CurrencyModel::factory()->create();

    $response = $this->postJson('/api/v1/brands', [
        'name' => ['en' => 'Test', 'ar' => 'Test'],
        'owner_id' => $this->client->id,
        'base_currency_id' => $currency->id,
    ]);

    $response->assertUnauthorized();
});

it('shows brand without auth', function () {
    $this->app->get('auth')->forgetGuards();
    $brand = BrandModel::factory()->create();

    $response = $this->getJson("/api/v1/brands/{$brand->id}");

    $response->assertOk();
});
