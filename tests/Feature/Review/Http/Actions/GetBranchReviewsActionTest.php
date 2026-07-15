<?php

declare(strict_types=1);

use Modules\Branch\Models\BranchModel;
use Modules\Review\Models\ReviewModel;

it('lists reviews for branch', function () {
    $branch = BranchModel::factory()->create();
    ReviewModel::factory()->count(3)->create([
        'subject_id' => $branch->id,
        'subject_type' => BranchModel::class,
    ]);

    $response = $this->getJson("/api/v1/branches/{$branch->id}/reviews");

    $response->assertOk();
    $response->assertJsonStructure([
        'success',
        'data' => ['*' => ['id', 'rating', 'comment']],
        'meta' => ['pagination'],
    ]);
    expect($response->json('data'))->toHaveCount(3);
});

it('returns empty for branch with no reviews', function () {
    $branch = BranchModel::factory()->create();

    $response = $this->getJson("/api/v1/branches/{$branch->id}/reviews");

    expect($response->json('data'))->toHaveCount(0);
});

it('paginates reviews for branch', function () {
    $branch = BranchModel::factory()->create();
    ReviewModel::factory()->count(25)->create([
        'subject_id' => $branch->id,
        'subject_type' => BranchModel::class,
    ]);

    $response = $this->getJson("/api/v1/branches/{$branch->id}/reviews?per_page=10");

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(10);
    expect($response->json('meta.pagination.per_page'))->toBe(10);
    expect($response->json('meta.pagination.total'))->toBe(25);
});

it('returns 404 for non-existent branch reviews', function () {
    $response = $this->getJson('/api/v1/branches/non-existent-id/reviews');

    $response->assertNotFound();
});
