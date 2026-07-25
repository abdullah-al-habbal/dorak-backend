<?php

declare(strict_types=1);

use Modules\Barber\Models\BarberModel;
use Modules\JobPosting\Models\ApplicationModel;

beforeEach(function () {
    $this->barber = BarberModel::factory()->create();
    $this->actingAs($this->barber, 'barber');
});

it('lists applications for authenticated barber', function () {
    ApplicationModel::factory()->count(2)->create([
        'barber_id' => $this->barber->id,
    ]);
    ApplicationModel::factory()->create();

    $response = $this->getJson('/api/v1/applications');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(2);
});

it('returns paginated response structure', function () {
    ApplicationModel::factory()->count(3)->create([
        'barber_id' => $this->barber->id,
    ]);

    $response = $this->getJson('/api/v1/applications');

    $response->assertOk();
    $response->assertJsonStructure([
        'success',
        'data',
        'meta' => ['pagination'],
    ]);
});

it('filters applications by status', function () {
    ApplicationModel::factory()->create([
        'barber_id' => $this->barber->id,
        'status' => 'submitted',
    ]);
    ApplicationModel::factory()->create([
        'barber_id' => $this->barber->id,
        'status' => 'accepted',
    ]);

    $response = $this->getJson('/api/v1/applications?status=accepted');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('data.0.status'))->toBe('accepted');
});

it('does not return other barbers applications', function () {
    ApplicationModel::factory()->create();

    $response = $this->getJson('/api/v1/applications');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(0);
});

it('requires barber authentication', function () {
    $this->app->get('auth')->forgetGuards();

    $response = $this->getJson('/api/v1/applications');

    $response->assertUnauthorized();
});
