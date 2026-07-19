<?php

declare(strict_types=1);

use Modules\Brand\Models\BrandModel;
use Modules\Client\Models\ClientModel;

beforeEach(function () {
    $this->client = ClientModel::factory()->create();
    $this->actingAs($this->client, 'client');
});

// Favorites
it('lists empty favorites', function () {
    $response = $this->getJson('/api/v1/client/favorites?per_page=20');

    $response->assertOk();
    expect($response->json('data'))->toBeArray();
    expect($response->json('data'))->toHaveCount(0);
});

it('adds a favorite', function () {
    $brand = BrandModel::factory()->create();

    $response = $this->postJson('/api/v1/client/favorites', [
        'favorable_id' => $brand->id,
        'favorable_type' => 'brand',
    ]);

    $response->assertCreated();
    expect($response->json('data'))->toHaveKeys(['id', 'favorable_id', 'favorable_type', 'created_at']);
    expect($response->json('data.favorable_type'))->toBe('brand');
});

it('returns 422 for invalid favorable_type', function () {
    $response = $this->postJson('/api/v1/client/favorites', [
        'favorable_id' => 'some-id',
        'favorable_type' => 'invalid_type',
    ]);

    $response->assertStatus(422);
});

it('removes a favorite', function () {
    $brand = BrandModel::factory()->create();

    $fav = $this->postJson('/api/v1/client/favorites', [
        'favorable_id' => $brand->id,
        'favorable_type' => 'brand',
    ])->json('data');

    $response = $this->deleteJson("/api/v1/client/favorites/{$fav['id']}");

    $response->assertOk();
});

it('fails favorites without auth', function () {
    $this->app->get('auth')->forgetGuards();

    $response = $this->getJson('/api/v1/client/favorites');

    $response->assertUnauthorized();
});

// Saved Filters
it('creates and lists saved filters', function () {
    $this->postJson('/api/v1/client/saved-filters', [
        'name' => 'My Filter',
        'filter_config' => ['universe' => 'men', 'radius' => 10],
    ])->assertCreated();

    $response = $this->getJson('/api/v1/client/saved-filters');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('data.0.name'))->toBe('My Filter');
});

it('shows a saved filter', function () {
    $created = $this->postJson('/api/v1/client/saved-filters', [
        'name' => 'My Filter',
        'filter_config' => ['universe' => 'men'],
    ])->json('data');

    $response = $this->getJson("/api/v1/client/saved-filters/{$created['id']}");

    $response->assertOk();
    expect($response->json('data.name'))->toBe('My Filter');
});

it('updates a saved filter', function () {
    $created = $this->postJson('/api/v1/client/saved-filters', [
        'name' => 'My Filter',
        'filter_config' => ['universe' => 'men'],
    ])->json('data');

    $response = $this->putJson("/api/v1/client/saved-filters/{$created['id']}", [
        'name' => 'Updated Filter',
        'filter_config' => ['universe' => 'women'],
    ]);

    $response->assertOk();
    expect($response->json('data.name'))->toBe('Updated Filter');
});

it('deletes a saved filter', function () {
    $created = $this->postJson('/api/v1/client/saved-filters', [
        'name' => 'My Filter',
        'filter_config' => ['universe' => 'men'],
    ])->json('data');

    $response = $this->deleteJson("/api/v1/client/saved-filters/{$created['id']}");

    $response->assertOk();
});

it('returns 422 creating saved filter without name', function () {
    $response = $this->postJson('/api/v1/client/saved-filters', [
        'filter_config' => ['universe' => 'men'],
    ]);

    $response->assertStatus(422);
});

// Discovery Preferences
it('returns default discovery preferences', function () {
    $response = $this->getJson('/api/v1/client/discovery-preferences');

    $response->assertOk();
    expect($response->json('data'))->toHaveKeys(['id', 'preferred_universe', 'default_radius', 'hidden_brand_ids', 'show_unavailable']);
});

it('updates discovery preferences', function () {
    $response = $this->patchJson('/api/v1/client/discovery-preferences', [
        'default_radius' => 50,
    ]);

    $response->assertOk();
    expect((int) $response->json('data.default_radius'))->toBe(50);
});

it('blocks saved filters without auth', function () {
    $this->app->get('auth')->forgetGuards();

    $response = $this->getJson('/api/v1/client/saved-filters');

    $response->assertUnauthorized();
});

it('blocks discovery preferences without auth', function () {
    $this->app->get('auth')->forgetGuards();

    $response = $this->getJson('/api/v1/client/discovery-preferences');

    $response->assertUnauthorized();
});
