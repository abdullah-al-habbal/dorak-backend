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

it('rejects second booking when same chair+slot already confirmed', function () {
    $chair = ChairModel::factory()->create();
    $timeSlot = Carbon::now()->addDay()->format('Y-m-d H:i:s');

    $this->postJson('/api/v1/bookings', [
        'chair_id' => $chair->id,
        'time_slot' => $timeSlot,
    ])->assertCreated();

    $response = $this->postJson('/api/v1/bookings', [
        'chair_id' => $chair->id,
        'time_slot' => $timeSlot,
    ]);

    $response->assertStatus(409);
    expect(BookingModel::where('chair_id', $chair->id)
        ->whereNotIn('status', ['canceled'])
        ->count()
    )->toBe(1);
});

it('allows bookings on different chairs for same time_slot', function () {
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

it('handles race condition: direct DB insert then API attempt', function () {
    $chair = ChairModel::factory()->create();
    $timeSlot = Carbon::now()->addDay()->format('Y-m-d H:i:s');

    BookingModel::create([
        'client_id' => $this->client->id,
        'chair_id' => $chair->id,
        'time_slot' => $timeSlot,
        'status' => 'confirmed',
    ]);

    $response = $this->postJson('/api/v1/bookings', [
        'chair_id' => $chair->id,
        'time_slot' => $timeSlot,
    ]);

    $response->assertStatus(409);
});

it('allows re-booking after cancellation on same chair+slot', function () {
    $chair = ChairModel::factory()->create();
    $timeSlot = Carbon::now()->addDay()->format('Y-m-d H:i:s');

    $this->postJson('/api/v1/bookings', [
        'chair_id' => $chair->id,
        'time_slot' => $timeSlot,
    ])->assertCreated();

    $booking = BookingModel::first();
    $booking->update(['status' => 'canceled']);

    $this->postJson('/api/v1/bookings', [
        'chair_id' => $chair->id,
        'time_slot' => $timeSlot,
    ])->assertCreated();

    expect(BookingModel::where('chair_id', $chair->id)
        ->where('time_slot', $timeSlot)
        ->count()
    )->toBe(2);
});
