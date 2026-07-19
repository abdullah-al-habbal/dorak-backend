<?php

declare(strict_types=1);

use Illuminate\Http\UploadedFile;
use Modules\Client\Models\ClientModel;

beforeEach(function () {
    $this->client = ClientModel::factory()->create();
});

it('uploads face photo and returns profile', function () {
    $file = UploadedFile::fake()->image('face.jpg', 400, 400);

    $response = $this->actingAs($this->client, 'client')
        ->postJson('/api/v1/client/face-profile', [
            'photo' => $file,
            'is_primary' => true,
        ]);

    $response->assertCreated();
    expect($response->json('data'))->toHaveKeys(['id', 'image_url', 'is_primary', 'uploaded_at']);
    expect($response->json('data.is_primary'))->toBeTrue();
});

it('uploads multiple photos', function () {
    $file1 = UploadedFile::fake()->image('face1.jpg', 400, 400);
    $file2 = UploadedFile::fake()->image('face2.jpg', 400, 400);

    $this->actingAs($this->client, 'client')
        ->postJson('/api/v1/client/face-profile', ['photo' => $file1, 'is_primary' => true]);

    $response = $this->actingAs($this->client, 'client')
        ->postJson('/api/v1/client/face-profile', ['photo' => $file2, 'is_primary' => false]);

    $response->assertCreated();
});

it('returns 422 without photo', function () {
    $response = $this->actingAs($this->client, 'client')
        ->postJson('/api/v1/client/face-profile', []);

    $response->assertStatus(422);
});

it('returns 401 without auth for upload', function () {
    $response = $this->postJson('/api/v1/client/face-profile', []);

    $response->assertUnauthorized();
});

it('returns recommendations list', function () {
    $response = $this->actingAs($this->client, 'client')
        ->getJson('/api/v1/client/face-profile/recommendations');

    $response->assertOk();
    expect($response->json('data'))->toBeArray();
});

it('returns 401 without auth for recommendations', function () {
    $response = $this->getJson('/api/v1/client/face-profile/recommendations');

    $response->assertUnauthorized();
});
