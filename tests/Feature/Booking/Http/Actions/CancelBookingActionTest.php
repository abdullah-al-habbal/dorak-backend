<?php

declare(strict_types=1);

use Modules\Booking\Models\BookingModel;
use Modules\Client\Models\ClientModel;

beforeEach(function () {
    $this->client = ClientModel::factory()->create();
    $this->actingAs($this->client, 'client');
});

it('cancels own confirmed booking', function () {
    $booking = BookingModel::factory()->create([
        'client_id' => $this->client->id,
        'status' => 'confirmed',
    ]);

    $response = $this->postJson("/api/v1/client/bookings/{$booking->id}/cancel");

    $response->assertOk();
    expect($response->json('data.status'))->toBe('canceled');
});

it('returns 403 when cancelling other users booking', function () {
    $booking = BookingModel::factory()->create(['status' => 'confirmed']);

    $response = $this->postJson("/api/v1/client/bookings/{$booking->id}/cancel");

    $response->assertForbidden();
});

it('returns 422 when cancelling non-confirmed booking', function () {
    $booking = BookingModel::factory()->completed()->create([
        'client_id' => $this->client->id,
    ]);

    $response = $this->postJson("/api/v1/client/bookings/{$booking->id}/cancel");

    $response->assertStatus(422);
});
