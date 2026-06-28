<?php
// tests/Feature/Core/Config/ConfigLoadingTest.php
declare(strict_types=1);

use Modules\Client\Models\ClientModel;

it('loads app config from Core module', function () {
    $this->assertNotNull(config('app.name'));
});

it('loads auth config with correct model', function () {
    $model = config('auth.providers.clients.model');
    $this->assertSame(ClientModel::class, $model);
});
