<?php

declare(strict_types=1);

use Modules\Booking\Models\BookingModel;
use Modules\Client\Models\ClientModel;

beforeEach(function () {
    $this->client = ClientModel::factory()->create();
    $this->actingAs($this->client, 'client');
});

it('submits review for completed booking', function () {
    $booking = BookingModel::factory()->completed()->create([
        'client_id' => $this->client->id,
    ]);

    $response = $this->postJson("/api/v1/client/bookings/{$booking->id}/review", [
        'rating' => 5,
        'comment' => 'Great service!',
    ]);

    $response->assertCreated();
    $response->assertJsonStructure([
        'success',
        'data' => ['id', 'rating', 'comment'],
    ]);
    expect($response->json('data.rating'))->toBe(5);
});

it('rejects review for non-completed booking', function () {
    $booking = BookingModel::factory()->create([
        'client_id' => $this->client->id,
        'status' => 'confirmed',
    ]);

    $response = $this->postJson("/api/v1/client/bookings/{$booking->id}/review", [
        'rating' => 5,
    ]);

    $response->assertStatus(422);
});

it('rejects duplicate review', function () {
    $booking = BookingModel::factory()->completed()->create([
        'client_id' => $this->client->id,
    ]);

    $this->postJson("/api/v1/client/bookings/{$booking->id}/review", [
        'rating' => 4,
    ]);

    $response = $this->postJson("/api/v1/client/bookings/{$booking->id}/review", [
        'rating' => 5,
    ]);

    $response->assertStatus(409);
});

it('rejects review with invalid rating', function () {
    $booking = BookingModel::factory()->completed()->create([
        'client_id' => $this->client->id,
    ]);

    $response = $this->postJson("/api/v1/client/bookings/{$booking->id}/review", [
        'rating' => 6,
    ]);

    $response->assertStatus(422);
});

it('rejects review for other users booking', function () {
    $booking = BookingModel::factory()->completed()->create();

    $response = $this->postJson("/api/v1/client/bookings/{$booking->id}/review", [
        'rating' => 5,
    ]);

    $response->assertForbidden();
});

it('includes author_name in review response', function () {
    $booking = BookingModel::factory()->completed()->create([
        'client_id' => $this->client->id,
    ]);

    $response = $this->postJson("/api/v1/client/bookings/{$booking->id}/review", [
        'rating' => 5,
        'comment' => 'Great!',
    ]);

    $response->assertCreated();
    expect($response->json('data.author_name'))->toBe($this->client->name);
});

it('rejects review for at-home booking without chair', function () {
    $booking = BookingModel::factory()->completed()->atHome()->create([
        'client_id' => $this->client->id,
    ]);

    $response = $this->postJson("/api/v1/client/bookings/{$booking->id}/review", [
        'rating' => 5,
    ]);

    $response->assertStatus(422);
});

it('rejects review with rating below minimum', function () {
    $booking = BookingModel::factory()->completed()->create([
        'client_id' => $this->client->id,
    ]);

    $response = $this->postJson("/api/v1/client/bookings/{$booking->id}/review", [
        'rating' => 0,
    ]);

    $response->assertStatus(422);
});

it('allows review without comment', function () {
    $booking = BookingModel::factory()->completed()->create([
        'client_id' => $this->client->id,
    ]);

    $response = $this->postJson("/api/v1/client/bookings/{$booking->id}/review", [
        'rating' => 5,
    ]);

    $response->assertCreated();
    expect($response->json('data.rating'))->toBe(5);
});
