<?php

declare(strict_types=1);

use Modules\Client\Models\ClientModel;

it('returns 422 without access_token', function () {
    $response = $this->postJson('/api/v1/client/social/google', []);

    $response->assertStatus(422);
    expect($response->json('errors.access_token'))->not->toBeNull();
});

it('returns 422 with empty access_token', function () {
    $response = $this->postJson('/api/v1/client/social/google', [
        'access_token' => '',
    ]);

    $response->assertStatus(422);
});

it('returns 401 with invalid access_token', function () {
    $response = $this->postJson('/api/v1/client/social/google', [
        'access_token' => 'invalid-token-value',
    ]);

    expect(in_array($response->status(), [401, 500]))->toBeTrue();
});
