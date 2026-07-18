<?php

declare(strict_types=1);

use Modules\Booking\Models\BookingModel;
use Modules\Client\Models\ClientModel;

beforeEach(function () {
    $this->client = ClientModel::factory()->create();
    $this->actingAs($this->client, 'client');
});

it('shows own booking', function () {
    $booking = BookingModel::factory()->create([
        'client_id' => $this->client->id,
    ]);

    $response = $this->getJson("/api/v1/bookings/{$booking->id}");

    $response->assertOk();
    expect($response->json('data.id'))->toBe($booking->id);
});

it('returns 403 for other users booking', function () {
    $booking = BookingModel::factory()->create();

    $response = $this->getJson("/api/v1/bookings/{$booking->id}");

    $response->assertForbidden();
});

it('returns 404 for non-existent booking', function () {
    $response = $this->getJson('/api/v1/bookings/00000000-0000-0000-0000-000000000000');

    $response->assertNotFound();
});
