<?php

declare(strict_types=1);

use Modules\Barber\Models\BarberModel;
use Modules\JobPosting\Models\JobPostingModel;

beforeEach(function () {
    $this->barber = BarberModel::factory()->create();
    $this->actingAs($this->barber, 'barber');
});

it('applies for open job', function () {
    $job = JobPostingModel::factory()->create(['status' => 'open']);

    $response = $this->postJson("/api/v1/jobs/{$job->id}/apply");

    $response->assertCreated();
    $response->assertJsonStructure([
        'success',
        'data' => ['id', 'job_posting_id', 'barber_id', 'status'],
    ]);
    expect($response->json('data.status'))->toBe('submitted');
});

it('rejects application for closed job', function () {
    $job = JobPostingModel::factory()->create(['status' => 'closed']);

    $response = $this->postJson("/api/v1/jobs/{$job->id}/apply");

    $response->assertStatus(422);
});

it('rejects duplicate application', function () {
    $job = JobPostingModel::factory()->create(['status' => 'open']);

    $this->postJson("/api/v1/jobs/{$job->id}/apply");
    $response = $this->postJson("/api/v1/jobs/{$job->id}/apply");

    $response->assertStatus(422);
});

it('requires barber authentication', function () {
    $this->app->get('auth')->forgetGuards();
    $job = JobPostingModel::factory()->create(['status' => 'open']);

    $response = $this->postJson("/api/v1/jobs/{$job->id}/apply");

    $response->assertUnauthorized();
});
