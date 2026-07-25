<?php

declare(strict_types=1);

use Modules\Barber\Models\BarberModel;

beforeEach(function () {
    $this->barber = BarberModel::factory()->create();
    $this->actingAs($this->barber, 'barber');
});

it('returns barber profile for authenticated barber', function () {
    $response = $this->getJson('/api/v1/barber/profile');

    $response->assertOk();
    expect($response->json('data.id'))->toBe($this->barber->id);
    expect($response->json('data.email'))->toBe($this->barber->email);
});

it('requires barber authentication for profile', function () {
    $this->app->get('auth')->forgetGuards();

    $response = $this->getJson('/api/v1/barber/profile');

    $response->assertUnauthorized();
});

it('updates profile fields', function () {
    $response = $this->patchJson('/api/v1/barber/profile', [
        'email' => 'updated@test.com',
        'is_freelancer' => true,
    ]);

    $response->assertOk();
    expect($response->json('data.email'))->toBe('updated@test.com');
    expect($response->json('data.is_freelancer'))->toBeTrue();
});

it('validates email uniqueness on profile update', function () {
    BarberModel::factory()->create(['email' => 'taken@test.com']);

    $response = $this->patchJson('/api/v1/barber/profile', [
        'email' => 'taken@test.com',
    ]);

    $response->assertStatus(422);
});

it('returns 422 with invalid profile data', function () {
    $response = $this->patchJson('/api/v1/barber/profile', [
        'email' => 'not-valid',
    ]);

    $response->assertStatus(422);
});
