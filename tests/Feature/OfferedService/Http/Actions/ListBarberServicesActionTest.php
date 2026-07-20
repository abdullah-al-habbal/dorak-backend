<?php

declare(strict_types=1);

use Modules\Barber\Models\BarberModel;
use Modules\Currency\Models\CurrencyModel;
use Modules\OfferedService\Models\OfferedServiceModel;
use Modules\ServiceCatalog\Models\ServiceCatalogItemModel;

it('lists barber services', function () {
    $barber = BarberModel::factory()->create();
    $currency = CurrencyModel::factory()->create();
    $item = ServiceCatalogItemModel::factory()->create();

    OfferedServiceModel::factory()->count(3)->create([
        'serviceable_id' => $barber->id,
        'serviceable_type' => 'barber',
        'catalog_item_id' => $item->id,
        'currency_id' => $currency->id,
    ]);

    $response = $this->getJson("/api/v1/barbers/{$barber->id}/services");

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(3);
    expect($response->json('data.0'))->toHaveKeys(['id', 'name', 'price', 'currency_id', 'duration']);
});

it('returns empty list when barber has no services', function () {
    $barber = BarberModel::factory()->create();

    $response = $this->getJson("/api/v1/barbers/{$barber->id}/services");

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(0);
});

it('returns 500 for non-existent barber', function () {
    $response = $this->getJson('/api/v1/barbers/' . fake()->uuid() . '/services');

    $response->assertStatus(404);
});
