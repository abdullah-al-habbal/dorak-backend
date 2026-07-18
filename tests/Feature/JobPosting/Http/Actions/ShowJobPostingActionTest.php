<?php

declare(strict_types=1);

use Modules\JobPosting\Models\JobPostingModel;

it('shows job posting', function () {
    $job = JobPostingModel::factory()->create();

    $response = $this->getJson("/api/v1/jobs/{$job->id}");

    $response->assertOk();
    expect($response->json('data.id'))->toBe($job->id);
});

it('returns 404 for non-existent job', function () {
    $response = $this->getJson('/api/v1/jobs/00000000-0000-0000-0000-000000000000');

    $response->assertNotFound();
});
