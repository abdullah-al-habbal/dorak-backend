<?php

declare(strict_types=1);

use Modules\Barber\Models\BarberModel;
use Modules\BarberAffiliation\Enums\AffiliationStatus;
use Modules\BarberAffiliation\Models\BarberAffiliationModel;
use Modules\Branch\Models\BranchModel;

beforeEach(function () {
    $this->barber = BarberModel::factory()->create();
    $this->actingAs($this->barber, 'barber');
});

it('creates affiliation', function () {
    $branch = BranchModel::factory()->create();

    $response = $this->postJson("/api/v1/barbers/{$this->barber->id}/affiliate", [
        'affiliable_id' => $branch->id,
        'affiliable_type' => 'branch',
    ]);

    $response->assertCreated();
    expect($response->json('data.status'))->toBe('pending');
    expect($response->json('data.barber_id'))->toBe($this->barber->id);
});

it('rejects affiliation with invalid type', function () {
    $response = $this->postJson("/api/v1/barbers/{$this->barber->id}/affiliate", [
        'affiliable_id' => 'some-id',
        'affiliable_type' => 'invalid-type',
    ]);

    $response->assertStatus(422);
});

it('rejects affiliation with missing fields', function () {
    $response = $this->postJson("/api/v1/barbers/{$this->barber->id}/affiliate", []);

    $response->assertStatus(422);
});

it('accepts pending affiliation', function () {
    $affiliation = BarberAffiliationModel::factory()->pending()->create([
        'barber_id' => $this->barber->id,
    ]);

    $response = $this->postJson("/api/v1/affiliations/{$affiliation->id}/accept");

    $response->assertOk();
    expect($response->json('data.status'))->toBe('accepted');
    expect($response->json('data.accepted_at'))->not->toBeNull();
});

it('rejects accepting non-pending affiliation', function () {
    $affiliation = BarberAffiliationModel::factory()->create([
        'barber_id' => $this->barber->id,
    ]);

    $response = $this->postJson("/api/v1/affiliations/{$affiliation->id}/accept");

    $response->assertStatus(400);
});

it('rejects pending affiliation', function () {
    $affiliation = BarberAffiliationModel::factory()->pending()->create([
        'barber_id' => $this->barber->id,
    ]);

    $response = $this->postJson("/api/v1/affiliations/{$affiliation->id}/reject");

    $response->assertOk();
    expect($response->json('data.status'))->toBe('rejected');
});

it('rejects rejecting non-pending affiliation', function () {
    $affiliation = BarberAffiliationModel::factory()->create([
        'barber_id' => $this->barber->id,
    ]);

    $response = $this->postJson("/api/v1/affiliations/{$affiliation->id}/reject");

    $response->assertStatus(400);
});

it('lists affiliations for barber', function () {
    BarberAffiliationModel::factory()->count(3)->create([
        'barber_id' => $this->barber->id,
    ]);

    $response = $this->getJson("/api/v1/barbers/{$this->barber->id}/affiliations");

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(3);
});

it('lists empty affiliations for barber with none', function () {
    $response = $this->getJson("/api/v1/barbers/{$this->barber->id}/affiliations");

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(0);
});

it('requires barber authentication for create', function () {
    $this->app->get('auth')->forgetGuards();
    $branch = BranchModel::factory()->create();

    $response = $this->postJson("/api/v1/barbers/{$this->barber->id}/affiliate", [
        'affiliable_id' => $branch->id,
        'affiliable_type' => 'branch',
    ]);

    $response->assertUnauthorized();
});

it('enforces multi-shop constraint: barber cannot affiliate to multiple branches', function () {
    $branchA = BranchModel::factory()->create();
    $branchB = BranchModel::factory()->create();

    BarberAffiliationModel::factory()->create([
        'barber_id' => $this->barber->id,
        'status' => AffiliationStatus::Accepted,
        'affiliable_id' => $branchA->id,
        'affiliable_type' => 'branch',
    ]);

    $response = $this->postJson("/api/v1/barbers/{$this->barber->id}/affiliate", [
        'affiliable_id' => $branchB->id,
        'affiliable_type' => 'branch',
    ]);

    $response->assertStatus(409);
});

it('requires barber authentication for accept', function () {
    $this->app->get('auth')->forgetGuards();
    $affiliation = BarberAffiliationModel::factory()->pending()->create();

    $response = $this->postJson("/api/v1/affiliations/{$affiliation->id}/accept");

    $response->assertUnauthorized();
});
