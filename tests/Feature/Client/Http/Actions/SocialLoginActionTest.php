<?php

declare(strict_types=1);

use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User;
use Modules\Client\Models\ClientModel;
use Modules\Client\Models\SocialAccountModel;

beforeEach(function () {
    $this->user = new User;
    $this->user->id = 'google-uid-123';
    $this->user->name = 'Test User';
    $this->user->email = 'social@example.com';
    $this->user->avatar = 'https://example.com/avatar.jpg';
});

it('returns 422 without access_token', function () {
    $response = $this->postJson('/api/v1/client/social/google', []);

    $response->assertStatus(422);
});

it('returns 422 with empty access_token', function () {
    $response = $this->postJson('/api/v1/client/social/google', [
        'access_token' => '',
    ]);

    $response->assertStatus(422);
});

it('returns 401 with invalid access_token', function () {
    Socialite::shouldReceive('driver')->with('google')->andThrow(new Exception('Invalid token'));

    $response = $this->postJson('/api/v1/client/social/google', [
        'access_token' => 'invalid-token-value',
    ]);

    $response->assertStatus(401);
});

it('creates new client via social login', function () {
    $stateless = Mockery::mock();
    $stateless->shouldReceive('userFromToken')->with('valid-google-token')->andReturn($this->user);

    $driver = Mockery::mock();
    $driver->shouldReceive('stateless')->andReturn($stateless);

    Socialite::shouldReceive('driver')->with('google')->andReturn($driver);

    $response = $this->postJson('/api/v1/client/social/google', [
        'access_token' => 'valid-google-token',
    ]);

    $response->assertStatus(200);
    expect($response->json('data.token'))->toBeString()->not->toBeEmpty();
    expect($response->json('data.client.email'))->toBe('social@example.com');
    expect($response->json('data.client.name'))->toBe('Test User');

    $this->assertDatabaseHas('clients', ['email' => 'social@example.com']);
    $this->assertDatabaseHas('social_accounts', [
        'provider' => 'google',
        'provider_id' => 'google-uid-123',
    ]);
});

it('returns existing client when social account already linked', function () {
    $client = ClientModel::factory()->create(['email' => 'existing@example.com']);
    SocialAccountModel::factory()->create([
        'client_id' => $client->id,
        'provider' => 'google',
        'provider_id' => 'google-existing-uid',
    ]);

    $user = new User;
    $user->id = 'google-existing-uid';
    $user->name = 'Existing User';
    $user->email = 'existing@example.com';
    $user->avatar = null;

    $stateless = Mockery::mock();
    $stateless->shouldReceive('userFromToken')->with('existing-token')->andReturn($user);

    $driver = Mockery::mock();
    $driver->shouldReceive('stateless')->andReturn($stateless);

    Socialite::shouldReceive('driver')->with('google')->andReturn($driver);

    $response = $this->postJson('/api/v1/client/social/google', [
        'access_token' => 'existing-token',
    ]);

    $response->assertStatus(200);
    expect($response->json('data.client.id'))->toBe($client->id);
    expect($response->json('data.token'))->toBeString()->not->toBeEmpty();
});

it('links social account to existing client by email', function () {
    $client = ClientModel::factory()->create(['email' => 'linked@example.com']);

    $user = new User;
    $user->id = 'google-new-link-uid';
    $user->name = 'Link User';
    $user->email = 'linked@example.com';
    $user->avatar = null;

    $stateless = Mockery::mock();
    $stateless->shouldReceive('userFromToken')->with('link-token')->andReturn($user);

    $driver = Mockery::mock();
    $driver->shouldReceive('stateless')->andReturn($stateless);

    Socialite::shouldReceive('driver')->with('google')->andReturn($driver);

    $response = $this->postJson('/api/v1/client/social/google', [
        'access_token' => 'link-token',
    ]);

    $response->assertStatus(200);
    expect($response->json('data.client.id'))->toBe($client->id);
    expect($client->socialAccounts()->count())->toBe(1);
});
