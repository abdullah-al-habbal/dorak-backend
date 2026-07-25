<?php

declare(strict_types=1);

use Modules\Barber\Models\BarberModel;
use Modules\Branch\Models\BranchModel;
use Modules\Chair\Models\ChairModel;
use Modules\JobPosting\Models\ApplicationModel;
use Modules\JobPosting\Models\JobPostingModel;

beforeEach(function () {
    $this->barber = BarberModel::factory()->create();
    $this->actingAs($this->barber, 'barber');
});

it('updates application status for branch owner', function () {
    $branch = BranchModel::factory()->create();
    ChairModel::factory()->create([
        'branch_id' => $branch->id,
        'barber_id' => $this->barber->id,
    ]);
    $job = JobPostingModel::factory()->create(['branch_id' => $branch->id]);
    $application = ApplicationModel::factory()->create([
        'job_posting_id' => $job->id,
    ]);

    $response = $this->putJson("/api/v1/applications/{$application->id}/status", [
        'status' => 'accepted',
    ]);

    $response->assertOk();
    expect($response->json('data.status'))->toBe('accepted');
});

it('rejects invalid status', function () {
    $branch = BranchModel::factory()->create();
    ChairModel::factory()->create([
        'branch_id' => $branch->id,
        'barber_id' => $this->barber->id,
    ]);
    $job = JobPostingModel::factory()->create(['branch_id' => $branch->id]);
    $application = ApplicationModel::factory()->create([
        'job_posting_id' => $job->id,
    ]);

    $response = $this->putJson("/api/v1/applications/{$application->id}/status", [
        'status' => 'invalid-status',
    ]);

    $response->assertStatus(422);
});

it('rejects update from non-branch barber', function () {
    $branch = BranchModel::factory()->create();
    $job = JobPostingModel::factory()->create(['branch_id' => $branch->id]);
    $application = ApplicationModel::factory()->create([
        'job_posting_id' => $job->id,
    ]);

    $response = $this->putJson("/api/v1/applications/{$application->id}/status", [
        'status' => 'accepted',
    ]);

    $response->assertForbidden();
});

it('returns 404 for non-existent application', function () {
    $response = $this->putJson('/api/v1/applications/00000000-0000-0000-0000-000000000000/status', [
        'status' => 'accepted',
    ]);

    $response->assertNotFound();
});

it('requires barber authentication', function () {
    $this->app->get('auth')->forgetGuards();
    $application = ApplicationModel::factory()->create();

    $response = $this->putJson("/api/v1/applications/{$application->id}/status", [
        'status' => 'accepted',
    ]);

    $response->assertUnauthorized();
});
