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
