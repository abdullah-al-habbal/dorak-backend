<?php

declare(strict_types=1);

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Barber\Models\BarberModel;
use Modules\Barber\Models\BarberPortfolioPhotoModel;

beforeEach(function () {
    $this->barber = BarberModel::factory()->create();
    $this->actingAs($this->barber, 'barber');
    Storage::fake('public');
});

it('uploads a portfolio photo', function () {
    $photo = UploadedFile::fake()->image('photo.jpg', 800, 600);

    $response = $this->postJson('/api/v1/barber/portfolio', [
        'photo' => $photo,
    ]);

    $response->assertCreated();
    expect($response->json('data.id'))->toBeString();
    expect($response->json('data.path'))->toContain('portfolio/');
    expect($response->json('data.url'))->toContain('portfolio/');
    expect($response->json('data.sort_order'))->toBe(1);
});

it('increments sort order on multiple uploads', function () {
    BarberPortfolioPhotoModel::factory()->create([
        'barber_id' => $this->barber->id,
        'sort_order' => 3,
    ]);

    $photo = UploadedFile::fake()->image('photo2.jpg', 800, 600);

    $response = $this->postJson('/api/v1/barber/portfolio', [
        'photo' => $photo,
    ]);

    $response->assertCreated();
    expect($response->json('data.sort_order'))->toBe(4);
});

it('rejects non-image file', function () {
    $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

    $response = $this->postJson('/api/v1/barber/portfolio', [
        'photo' => $file,
    ]);

    $response->assertStatus(422);
});

it('rejects oversized file', function () {
    $photo = UploadedFile::fake()->image('photo.jpg', 800, 600)->size(6000);

    $response = $this->postJson('/api/v1/barber/portfolio', [
        'photo' => $photo,
    ]);

    $response->assertStatus(422);
});

it('requires authentication for upload', function () {
    $this->app->get('auth')->forgetGuards();

    $photo = UploadedFile::fake()->image('photo.jpg', 800, 600);

    $response = $this->postJson('/api/v1/barber/portfolio', [
        'photo' => $photo,
    ]);

    $response->assertUnauthorized();
});

it('deletes a portfolio photo', function () {
    $photo = BarberPortfolioPhotoModel::factory()->create([
        'barber_id' => $this->barber->id,
        'path' => 'portfolio/' . $this->barber->id . '/test.jpg',
    ]);

    $response = $this->deleteJson("/api/v1/barber/portfolio/{$photo->id}");

    $response->assertOk();
    $this->assertDatabaseMissing('barber_portfolio_photos', ['id' => $photo->id]);
});

it('returns 404 when deleting non-existent photo', function () {
    $fakeId = '00000000-0000-0000-0000-000000000000';

    $response = $this->deleteJson("/api/v1/barber/portfolio/{$fakeId}");

    $response->assertStatus(404);
});

it('requires authentication for delete', function () {
    $this->app->get('auth')->forgetGuards();

    $response = $this->deleteJson('/api/v1/barber/portfolio/some-id');

    $response->assertUnauthorized();
});
