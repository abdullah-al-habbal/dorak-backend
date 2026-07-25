<?php

declare(strict_types=1);

use Modules\Admin\Models\AdminModel;
use Modules\Currency\Models\CurrencyModel;
use Modules\Currency\Models\ExchangeRateModel;

beforeEach(function () {
    $this->admin = AdminModel::factory()->create();
    $this->actingAs($this->admin, 'admin');
});

it('converts between currencies', function () {
    $usd = CurrencyModel::factory()->create(['code' => 'USD']);
    $eur = CurrencyModel::factory()->create(['code' => 'EUR']);
    ExchangeRateModel::factory()->create([
        'from_currency_id' => $usd->id,
        'to_currency_id' => $eur->id,
        'rate' => 0.85,
    ]);

    $response = $this->postJson('/api/v1/currency/convert', [
        'from' => 'USD',
        'to' => 'EUR',
        'amount' => 100,
    ]);

    $response->assertOk();
    expect($response->json('data.result'))->toEqual(85.0);
    expect($response->json('data.rate'))->toEqual(0.85);
});

it('returns same amount for same currency', function () {
    CurrencyModel::factory()->create(['code' => 'USD']);

    $response = $this->postJson('/api/v1/currency/convert', [
        'from' => 'USD',
        'to' => 'USD',
        'amount' => 100,
    ]);

    $response->assertOk();
    expect($response->json('data.result'))->toEqual(100.0);
    expect($response->json('data.rate'))->toEqual(1.0);
});

it('returns 404 for missing exchange rate', function () {
    CurrencyModel::factory()->create(['code' => 'USD']);
    CurrencyModel::factory()->create(['code' => 'EUR']);

    $response = $this->postJson('/api/v1/currency/convert', [
        'from' => 'USD',
        'to' => 'EUR',
        'amount' => 100,
    ]);

    $response->assertNotFound();
});

it('requires from and to parameters', function () {
    $response = $this->postJson('/api/v1/currency/convert', [
        'amount' => 100,
    ]);

    $response->assertStatus(422);
});

it('returns 401 when unauthenticated', function () {
    auth()->guard('admin')->logout();

    $response = $this->postJson('/api/v1/currency/convert', [
        'from' => 'USD',
        'to' => 'EUR',
        'amount' => 100,
    ]);

    $response->assertUnauthorized();
});
