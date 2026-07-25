<?php

declare(strict_types=1);

use Modules\Barber\Models\BarberModel;

it('returns 422 without credentials', function () {
    $response = $this->postJson('/api/v1/barber/login', []);

    $response->assertStatus(422);
});

it('returns 422 with invalid email', function () {
    $response = $this->postJson('/api/v1/barber/login', [
        'email' => 'not-an-email',
        'password' => 'password',
    ]);

    $response->assertStatus(422);
});

it('returns 401 with wrong password', function () {
    BarberModel::factory()->create(['email' => 'barber@test.com']);

    $response = $this->postJson('/api/v1/barber/login', [
        'email' => 'barber@test.com',
        'password' => 'wrong-password',
    ]);

    $response->assertUnauthorized();
});

it('returns 401 with non-existent email', function () {
    $response = $this->postJson('/api/v1/barber/login', [
        'email' => 'nonexistent@test.com',
        'password' => 'password',
    ]);

    $response->assertUnauthorized();
});

it('returns token with valid credentials', function () {
    BarberModel::factory()->create([
        'email' => 'barber@test.com',
        'password' => bcrypt('secret123'),
    ]);

    $response = $this->postJson('/api/v1/barber/login', [
        'email' => 'barber@test.com',
        'password' => 'secret123',
    ]);

    $response->assertOk();
    expect($response->json('data.token'))->toBeString()->not->toBeEmpty();
    expect($response->json('data.barber.email'))->toBe('barber@test.com');
});
