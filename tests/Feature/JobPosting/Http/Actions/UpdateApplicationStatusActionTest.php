<?php

declare(strict_types=1);

use Modules\Barber\Models\BarberModel;
use Modules\JobPosting\Models\ApplicationModel;

beforeEach(function () {
    $this->barber = BarberModel::factory()->create();
    $this->actingAs($this->barber, 'barber');
});

it('updates application status', function () {
    $application = ApplicationModel::factory()->create();

    $response = $this->putJson("/api/v1/applications/{$application->id}/status", [
        'status' => 'reviewed',
    ]);

    $response->assertOk();
    expect($response->json('data.status'))->toBe('reviewed');
});

it('rejects invalid status', function () {
    $application = ApplicationModel::factory()->create();

    $response = $this->putJson("/api/v1/applications/{$application->id}/status", [
        'status' => 'invalid-status',
    ]);

    $response->assertStatus(422);
});
