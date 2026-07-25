<?php

declare(strict_types=1);

use Modules\Booking\Models\BookingModel;
use Modules\Branch\Models\BranchModel;
use Modules\Client\Models\ClientModel;
use Modules\Review\Models\ReviewModel;

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
        'comment' => 'Excellent!',
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

it('rejects duplicate review on same booking', function () {
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

it('lists reviews by branch with pagination', function () {
    $branch = BranchModel::factory()->create();
    ReviewModel::factory()->count(15)->create([
        'subject_id' => $branch->id,
        'subject_type' => BranchModel::class,
    ]);

    $response = $this->getJson("/api/v1/branches/{$branch->id}/reviews?per_page=10");

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(10);
    expect($response->json('meta.pagination.total'))->toBe(15);
});
