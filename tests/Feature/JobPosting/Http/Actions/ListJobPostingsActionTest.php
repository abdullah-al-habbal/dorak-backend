<?php

declare(strict_types=1);

use Modules\JobPosting\Models\JobPostingModel;

it('lists open job postings', function () {
    JobPostingModel::factory()->count(3)->create(['status' => 'open']);

    $response = $this->getJson('/api/v1/jobs');

    $response->assertOk();
    $response->assertJsonStructure([
        'success',
        'data' => ['*' => ['id', 'title', 'status']],
        'meta' => ['pagination'],
    ]);
    expect($response->json('data'))->toHaveCount(3);
});

it('does not include closed jobs', function () {
    JobPostingModel::factory()->create(['status' => 'open']);
    JobPostingModel::factory()->create(['status' => 'closed']);

    $response = $this->getJson('/api/v1/jobs');

    expect($response->json('data'))->toHaveCount(1);
});

it('returns empty when no jobs exist', function () {
    $response = $this->getJson('/api/v1/jobs');

    expect($response->json('data'))->toHaveCount(0);
});
