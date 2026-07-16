<?php

declare(strict_types=1);

use Carbon\Carbon;
use Modules\Booking\Models\BookingModel;
use Modules\Chair\Models\ChairModel;
use Modules\Client\Models\ClientModel;

beforeEach(function () {
    $this->client = ClientModel::factory()->create();
    $this->actingAs($this->client, 'client');
});

it('creates a booking with chair_id', function () {
    $chair = ChairModel::factory()->create();
    $timeSlot = Carbon::now()->addDay()->format('Y-m-d H:i:s');

    $response = $this->postJson('/api/v1/bookings', [
        'chair_id' => $chair->id,
        'time_slot' => $timeSlot,
    ]);

    $response->assertCreated();
    $response->assertJsonStructure([
        'success',
        'data' => ['id', 'time_slot', 'status', 'chair'],
    ]);
    expect($response->json('data.status'))->toBe('confirmed');
});

it('rejects double booking on same chair and time_slot', function () {
    $chair = ChairModel::factory()->create();
    $timeSlot = Carbon::now()->addDay()->format('Y-m-d H:i:s');

    $this->postJson('/api/v1/bookings', [
        'chair_id' => $chair->id,
        'time_slot' => $timeSlot,
    ]);

    $response = $this->postJson('/api/v1/bookings', [
        'chair_id' => $chair->id,
        'time_slot' => $timeSlot,
    ]);

    $response->assertStatus(409);
});

it('rejects booking on unavailable chair', function () {
    $chair = ChairModel::factory()->create();
    $chair->update(['status' => 'maintenance']);
    $timeSlot = Carbon::now()->addDay()->format('Y-m-d H:i:s');

    $response = $this->postJson('/api/v1/bookings', [
        'chair_id' => $chair->id,
        'time_slot' => $timeSlot,
    ]);

    $response->assertStatus(409);
});

it('rejects booking with past time_slot', function () {
    $chair = ChairModel::factory()->create();
    $timeSlot = Carbon::now()->subDay()->format('Y-m-d H:i:s');

    $response = $this->postJson('/api/v1/bookings', [
        'chair_id' => $chair->id,
        'time_slot' => $timeSlot,
    ]);

    $response->assertStatus(422);
});

it('creates at-home booking without chair_id', function () {
    $timeSlot = Carbon::now()->addDay()->format('Y-m-d H:i:s');

    $response = $this->postJson('/api/v1/bookings', [
        'at_home_location' => ['lat' => 33.5, 'lng' => 36.3],
        'time_slot' => $timeSlot,
    ]);

    $response->assertCreated();
    expect($response->json('data.status'))->toBe('confirmed');
});

it('rejects booking with both chair_id and at_home_location', function () {
    $chair = ChairModel::factory()->create();
    $timeSlot = Carbon::now()->addDay()->format('Y-m-d H:i:s');

    $response = $this->postJson('/api/v1/bookings', [
        'chair_id' => $chair->id,
        'at_home_location' => ['lat' => 33.5, 'lng' => 36.3],
        'time_slot' => $timeSlot,
    ]);

    $response->assertStatus(422);
});

it('ensures only one booking per chair+slot in database after conflict', function () {
    $chair = ChairModel::factory()->create();
    $timeSlot = Carbon::now()->addDay()->format('Y-m-d H:i:s');

    $this->postJson('/api/v1/bookings', [
        'chair_id' => $chair->id,
        'time_slot' => $timeSlot,
    ])->assertCreated();

    $this->postJson('/api/v1/bookings', [
        'chair_id' => $chair->id,
        'time_slot' => $timeSlot,
    ])->assertStatus(409);

    expect(BookingModel::where('chair_id', $chair->id)
        ->whereNotIn('status', ['canceled'])
        ->count()
    )->toBe(1);
});

it('allows booking after a cancelled booking on same chair+slot', function () {
    $chair = ChairModel::factory()->create();
    $timeSlot = Carbon::now()->addDay()->format('Y-m-d H:i:s');

    $this->postJson('/api/v1/bookings', [
        'chair_id' => $chair->id,
        'time_slot' => $timeSlot,
    ])->assertCreated();

    // Cancel the booking
    $booking = BookingModel::first();
    $booking->update(['status' => 'canceled']);

    // Now booking the same slot should succeed
    $this->postJson('/api/v1/bookings', [
        'chair_id' => $chair->id,
        'time_slot' => $timeSlot,
    ])->assertCreated();
});

it('allows different chairs for same time_slot', function () {
    $chairA = ChairModel::factory()->create();
    $chairB = ChairModel::factory()->create();
    $timeSlot = Carbon::now()->addDay()->format('Y-m-d H:i:s');

    $this->postJson('/api/v1/bookings', [
        'chair_id' => $chairA->id,
        'time_slot' => $timeSlot,
    ])->assertCreated();

    $this->postJson('/api/v1/bookings', [
        'chair_id' => $chairB->id,
        'time_slot' => $timeSlot,
    ])->assertCreated();

    expect(BookingModel::where('time_slot', $timeSlot)->count())->toBe(2);
});

it('prevents race condition: concurrent booking for same chair+slot', function () {
    $chair = ChairModel::factory()->create();
    $timeSlot = Carbon::now()->addDay()->format('Y-m-d H:i:s');

    // Simulate concurrent request: insert a booking directly (bypassing check)
    // then verify API rejects a second one
    BookingModel::create([
        'client_id' => $this->client->id,
        'chair_id' => $chair->id,
        'time_slot' => $timeSlot,
        'status' => 'confirmed',
    ]);

    $this->postJson('/api/v1/bookings', [
        'chair_id' => $chair->id,
        'time_slot' => $timeSlot,
    ])->assertStatus(409);
});

it('requires authentication', function () {
    $this->app->get('auth')->forgetGuards();

    $response = $this->postJson('/api/v1/bookings', [
        'chair_id' => 'some-id',
        'time_slot' => Carbon::now()->addDay()->format('Y-m-d H:i:s'),
    ]);

    $response->assertUnauthorized();
});
