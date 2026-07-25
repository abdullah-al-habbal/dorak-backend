<?php

declare(strict_types=1);

use Modules\Ban\Models\BanModel;
use Modules\Client\Models\ClientModel;

beforeEach(function () {
    $this->client = ClientModel::factory()->create();
    $this->actingAs($this->client, 'client');
});

it('returns banned false when client has no active ban', function () {
    $response = $this->getJson("/api/v1/clients/{$this->client->id}/bans/check");

    $response->assertOk();
    expect($response->json('data.banned'))->toBeFalse();
    expect($response->json('data.bans'))->toBeEmpty();
});

it('returns banned true when client has active ban', function () {
    BanModel::factory()->create([
        'bannable_id' => $this->client->id,
        'bannable_type' => 'client',
        'banned_from' => now()->subDay(),
        'banned_until' => now()->addDay(),
    ]);

    $response = $this->getJson("/api/v1/clients/{$this->client->id}/bans/check");

    $response->assertOk();
    expect($response->json('data.banned'))->toBeTrue();
    expect($response->json('data.bans'))->not->toBeEmpty();
});

it('returns banned false when ban has expired', function () {
    BanModel::factory()->create([
        'bannable_id' => $this->client->id,
        'bannable_type' => 'client',
        'banned_from' => now()->subDays(10),
        'banned_until' => now()->subDay(),
    ]);

    $response = $this->getJson("/api/v1/clients/{$this->client->id}/bans/check");

    $response->assertOk();
    expect($response->json('data.banned'))->toBeFalse();
    expect($response->json('data.bans'))->toBeEmpty();
});

it('returns 401 when not authenticated', function () {
    $this->app['auth']->forgetGuards();

    $client = ClientModel::factory()->create();

    $response = $this->getJson("/api/v1/clients/{$client->id}/bans/check");

    $response->assertUnauthorized();
});

it('returns 403 when checking another clients bans', function () {
    $otherClient = ClientModel::factory()->create();

    $response = $this->getJson("/api/v1/clients/{$otherClient->id}/bans/check");

    $response->assertForbidden();
});

it('returns 404 for non-existent client', function () {
    $response = $this->getJson('/api/v1/clients/' . fake()->uuid() . '/bans/check');

    $response->assertNotFound();
});
