<?php

declare(strict_types=1);

use Modules\Currency\Models\CurrencyModel;

it('lists all currencies', function () {
    CurrencyModel::factory()->count(3)->create();

    $response = $this->getJson('/api/v1/currencies');

    $response->assertOk();
    $response->assertJsonStructure([
        'success',
        'data' => ['*' => ['id', 'code', 'symbol', 'is_default']],
    ]);
    expect($response->json('data'))->toHaveCount(3);
});

it('returns empty when no currencies exist', function () {
    $response = $this->getJson('/api/v1/currencies');

    expect($response->json('data'))->toHaveCount(0);
});
