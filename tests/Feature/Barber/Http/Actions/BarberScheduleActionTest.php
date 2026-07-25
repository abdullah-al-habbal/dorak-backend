<?php

declare(strict_types=1);

use Modules\Barber\Models\BarberModel;
use Modules\Barber\Models\BarberScheduleModel;

beforeEach(function () {
    $this->barber = BarberModel::factory()->create();
    $this->actingAs($this->barber, 'barber');
});

it('gets empty schedule for new barber', function () {
    $response = $this->getJson('/api/v1/barber/schedule');

    $response->assertOk();
    expect($response->json('data'))->toBeArray()->toHaveCount(0);
});

it('gets schedule with entries', function () {
    BarberScheduleModel::factory()->create([
        'barber_id' => $this->barber->id,
        'day_of_week' => 1,
    ]);

    $response = $this->getJson('/api/v1/barber/schedule');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(1);
});

it('requires authentication for get schedule', function () {
    $this->app->get('auth')->forgetGuards();

    $response = $this->getJson('/api/v1/barber/schedule');

    $response->assertUnauthorized();
});

it('updates schedule', function () {
    $response = $this->patchJson('/api/v1/barber/schedule', [
        'schedule' => [
            ['day_of_week' => 1, 'start_time' => '09:00', 'end_time' => '17:00', 'is_active' => true],
            ['day_of_week' => 2, 'start_time' => '09:00', 'end_time' => '17:00', 'is_active' => true],
            ['day_of_week' => 3, 'start_time' => '10:00', 'end_time' => '15:00', 'is_active' => false],
        ],
    ]);

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(3);
});

it('replaces existing schedule on update', function () {
    BarberScheduleModel::factory()->create([
        'barber_id' => $this->barber->id,
        'day_of_week' => 0,
    ]);

    $response = $this->patchJson('/api/v1/barber/schedule', [
        'schedule' => [
            ['day_of_week' => 5, 'start_time' => '08:00', 'end_time' => '16:00', 'is_active' => true],
        ],
    ]);

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('data.0.day_of_week'))->toBe(5);
});

it('validates schedule entry fields', function () {
    $response = $this->patchJson('/api/v1/barber/schedule', [
        'schedule' => [
            ['day_of_week' => 8, 'start_time' => '09:00', 'end_time' => '17:00', 'is_active' => true],
        ],
    ]);

    $response->assertStatus(422);
});

it('validates schedule is not empty', function () {
    $response = $this->patchJson('/api/v1/barber/schedule', [
        'schedule' => [],
    ]);

    $response->assertStatus(422);
});

it('requires authentication for update schedule', function () {
    $this->app->get('auth')->forgetGuards();

    $response = $this->patchJson('/api/v1/barber/schedule', [
        'schedule' => [
            ['day_of_week' => 1, 'start_time' => '09:00', 'end_time' => '17:00', 'is_active' => true],
        ],
    ]);

    $response->assertUnauthorized();
});
