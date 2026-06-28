<?php
// tests/Feature/ConfigLoadingTest.php
declare(strict_types=1);

use Modules\Core\Models\UserModel;

it('loads app config from Core module', function () {
    $this->assertNotNull(config('app.name'));
});

it('loads auth config with correct model', function () {
    $model = config('auth.providers.users.model');
    $this->assertSame(UserModel::class, $model);
});
