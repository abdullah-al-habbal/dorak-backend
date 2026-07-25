<?php

declare(strict_types=1);

use Modules\Brand\Models\BrandModel;
use Modules\Client\Models\ClientModel;
use Modules\Currency\Models\CurrencyModel;

beforeEach(function () {
    $this->client = ClientModel::factory()->create();
    $this->actingAs($this->client, 'client');
});

it('creates a brand with valid data', function () {
    $currency = CurrencyModel::factory()->create();

    $response = $this->postJson('/api/v1/brands', [
        'name' => ['en' => 'Barber Shop EN', 'ar' => 'Barber Shop AR'],
        'base_currency_id' => $currency->id,
        'logo' => 'https://example.com/logo.png',
        'description' => ['en' => 'A great shop', 'ar' => 'محل رائع'],
    ]);

    $response->assertCreated();
    $response->assertJsonStructure([
        'success',
        'data' => ['id', 'name', 'description', 'logo', 'owner', 'base_currency', 'created_at'],
    ]);
    expect($response->json('data.name.en'))->toBe('Barber Shop EN');
    expect($response->json('data.name.ar'))->toBe('Barber Shop AR');
    expect($response->json('data.logo'))->toBe('https://example.com/logo.png');
    expect($response->json('data.owner.id'))->toBe($this->client->id);

    $this->assertDatabaseHas('brands', [
        'owner_id' => $this->client->id,
    ]);
});

it('creates a brand with only required fields', function () {
    $currency = CurrencyModel::factory()->create();

    $response = $this->postJson('/api/v1/brands', [
        'name' => ['en' => 'Minimal Brand', 'ar' => 'علامة تجارية'],
        'base_currency_id' => $currency->id,
    ]);

    $response->assertCreated();
    expect($response->json('data.name.en'))->toBe('Minimal Brand');
});

it('auto-sets owner_id from authenticated client', function () {
    $currency = CurrencyModel::factory()->create();

    $this->postJson('/api/v1/brands', [
        'name' => ['en' => 'My Brand', 'ar' => 'علامتي'],
        'base_currency_id' => $currency->id,
    ]);

    $brand = BrandModel::where('owner_id', $this->client->id)->first();
    expect($brand)->not->toBeNull();
});

it('rejects create with missing name', function () {
    $response = $this->postJson('/api/v1/brands', [
        'base_currency_id' => '00000000-0000-0000-0000-000000000000',
    ]);

    $response->assertStatus(422);
});

it('rejects create with missing name.en', function () {
    $currency = CurrencyModel::factory()->create();

    $response = $this->postJson('/api/v1/brands', [
        'name' => ['ar' => 'فقط عربي'],
        'base_currency_id' => $currency->id,
    ]);

    $response->assertStatus(422);
});

it('rejects create with missing base_currency_id', function () {
    $response = $this->postJson('/api/v1/brands', [
        'name' => ['en' => 'Brand', 'ar' => 'علامة'],
    ]);

    $response->assertStatus(422);
});

it('rejects create with invalid base_currency_id', function () {
    $response = $this->postJson('/api/v1/brands', [
        'name' => ['en' => 'Brand', 'ar' => 'علامة'],
        'base_currency_id' => '00000000-0000-0000-0000-000000000000',
    ]);

    $response->assertStatus(422);
});

it('rejects create with invalid logo url', function () {
    $currency = CurrencyModel::factory()->create();

    $response = $this->postJson('/api/v1/brands', [
        'name' => ['en' => 'Brand', 'ar' => 'علامة'],
        'base_currency_id' => $currency->id,
        'logo' => 'not-a-url',
    ]);

    $response->assertStatus(422);
});

it('rejects unauthorized brand create', function () {
    $this->app->get('auth')->forgetGuards();
    $currency = CurrencyModel::factory()->create();

    $response = $this->postJson('/api/v1/brands', [
        'name' => ['en' => 'Test', 'ar' => 'Test'],
        'base_currency_id' => $currency->id,
    ]);

    $response->assertUnauthorized();
});
