<?php

declare(strict_types=1);

use Modules\Barber\Models\BarberModel;
use Modules\JobPosting\Models\ApplicationModel;

beforeEach(function () {
    $this->barber = BarberModel::factory()->create();
    $this->actingAs($this->barber, 'barber');
});

it('lists applications filtered by barber_id', function () {
    ApplicationModel::factory()->count(2)->create([
        'barber_id' => $this->barber->id,
    ]);
    ApplicationModel::factory()->create();

    $response = $this->getJson('/api/v1/applications?barber_id=' . $this->barber->id);

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(2);
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

    $response = $this->getJson('/api/v1/applications?barber_id=' . $this->barber->id . '&status=accepted');

    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('data.0.status'))->toBe('accepted');
});
