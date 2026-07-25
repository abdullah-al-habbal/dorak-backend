<?php

declare(strict_types=1);

use Modules\Barber\Models\BarberModel;

beforeEach(function () {
    $this->barber = BarberModel::factory()->create();
    $this->actingAs($this->barber, 'barber');
});

it('activates a barber', function () {
    $response = $this->postJson("/api/v1/barbers/{$this->barber->id}/activate", [
        'reason' => 'Approved for platform',
    ]);

    $response->assertCreated();
    expect($response->json('data.status'))->toBe('enabled');
    expect($response->json('data.activable_id'))->toBe($this->barber->id);
});

it('deactivates a barber', function () {
    $response = $this->postJson("/api/v1/barbers/{$this->barber->id}/deactivate", [
        'reason' => 'Violation of terms',
    ]);

    $response->assertCreated();
    expect($response->json('data.status'))->toBe('disabled');
});

it('activates without reason', function () {
    $response = $this->postJson("/api/v1/barbers/{$this->barber->id}/activate");

    $response->assertCreated();
    expect($response->json('data.status'))->toBe('enabled');
});

it('returns 404 for non-existent barber activation', function () {
    $response = $this->postJson('/api/v1/barbers/00000000-0000-0000-0000-000000000000/activate');

    $response->assertNotFound();
});

it('returns 404 for non-existent barber deactivation', function () {
    $response = $this->postJson('/api/v1/barbers/00000000-0000-0000-0000-000000000000/deactivate');

    $response->assertNotFound();
});
