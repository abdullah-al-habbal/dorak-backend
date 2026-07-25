<?php

declare(strict_types=1);

use Modules\Admin\Models\AdminModel;
use Modules\Currency\Models\ExchangeRateModel;

beforeEach(function () {
    $this->admin = AdminModel::factory()->create();
    $this->actingAs($this->admin, 'admin');
});

it('lists all exchange rates', function () {
    ExchangeRateModel::factory()->count(3)->create();

    $response = $this->getJson('/api/v1/exchange-rates');

    $response->assertOk();
    $response->assertJsonStructure([
        'success',
        'code',
        'data' => ['*' => ['id', 'from_currency_id', 'to_currency_id', 'rate', 'effective_at']],
    ]);
    expect($response->json('data'))->toHaveCount(3);
});

it('returns empty array when no exchange rates exist', function () {
    $response = $this->getJson('/api/v1/exchange-rates');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(0);
});

it('returns 401 when unauthenticated', function () {
    auth()->guard('admin')->logout();

    $response = $this->getJson('/api/v1/exchange-rates');

    $response->assertUnauthorized();
});
