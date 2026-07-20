<?php

declare(strict_types=1);

use Modules\Ban\Models\BanModel;
use Modules\Client\Models\ClientModel;

it('returns is_banned false when client has no active ban', function () {
    $client = ClientModel::factory()->create();

    $response = $this->getJson("/api/v1/clients/{$client->id}/bans/check");

    $response->assertOk();
    expect($response->json('data.is_banned'))->toBeFalse();
});

it('returns is_banned true when client has active ban', function () {
    $client = ClientModel::factory()->create();

    BanModel::factory()->create([
        'bannable_id' => $client->id,
        'bannable_type' => 'client',
        'banned_from' => now()->subDay(),
        'banned_until' => now()->addDay(),
    ]);

    $response = $this->getJson("/api/v1/clients/{$client->id}/bans/check");

    $response->assertOk();
    expect($response->json('data.is_banned'))->toBeTrue();
});

it('returns is_banned false when ban has expired', function () {
    $client = ClientModel::factory()->create();

    BanModel::factory()->create([
        'bannable_id' => $client->id,
        'bannable_type' => 'client',
        'banned_from' => now()->subDays(10),
        'banned_until' => now()->subDay(),
    ]);

    $response = $this->getJson("/api/v1/clients/{$client->id}/bans/check");

    $response->assertOk();
    expect($response->json('data.is_banned'))->toBeFalse();
});

it('returns 404 for non-existent client', function () {
    $response = $this->getJson('/api/v1/clients/' . fake()->uuid() . '/bans/check');

    $response->assertStatus(404);
});
