<?php

declare(strict_types=1);

use Modules\Activation\Models\ActivationLogModel;
use Modules\Barber\Models\BarberModel;

beforeEach(function () {
    $this->barber = BarberModel::factory()->create();
    $this->actingAs($this->barber, 'barber');
});

it('activates a barber', function () {
    $response = $this->postJson("/api/v1/barbers/{$this->barber->id}/activate");

    $response->assertCreated();
    expect($response->json('data.status'))->toBe('enabled');
    expect($response->json('data.activable_id'))->toBe($this->barber->id);
    expect(ActivationLogModel::where('activable_id', $this->barber->id)->count())->toBe(1);
});

it('activates a barber with reason', function () {
    $response = $this->postJson("/api/v1/barbers/{$this->barber->id}/activate", [
        'reason' => 'Approved for platform',
    ]);

    $response->assertCreated();
    expect($response->json('data.status'))->toBe('enabled');
});

it('returns 401 without authentication', function () {
    $this->app->get('auth')->forgetGuards();

    $response = $this->postJson("/api/v1/barbers/{$this->barber->id}/activate");

    $response->assertUnauthorized();
});

it('returns 403 when activating another barber', function () {
    $otherBarber = BarberModel::factory()->create();

    $response = $this->postJson("/api/v1/barbers/{$otherBarber->id}/activate");

    $response->assertForbidden();
});

it('returns 404 for non-existent barber', function () {
    $response = $this->postJson('/api/v1/barbers/00000000-0000-0000-0000-000000000000/activate');

    $response->assertNotFound();
});
