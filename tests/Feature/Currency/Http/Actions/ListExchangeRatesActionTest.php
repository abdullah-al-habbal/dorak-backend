<?php

declare(strict_types=1);

use Modules\Currency\Models\ExchangeRateModel;

it('lists all exchange rates', function () {
    ExchangeRateModel::factory()->count(3)->create();

    $response = $this->getJson('/api/v1/exchange-rates');

    $response->assertOk();
    $response->assertJsonStructure([
        'success',
        'data' => ['*' => ['id', 'from_currency_id', 'to_currency_id', 'rate']],
    ]);
    expect($response->json('data'))->toHaveCount(3);
});
