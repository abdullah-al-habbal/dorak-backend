<?php

// tests/Feature/Core/Config/ConfigLoadingTest.php
declare(strict_types=1);

use Modules\Admin\Models\AdminModel;
use Modules\Barber\Models\BarberModel;
use Modules\Branch\Models\BranchModel;
use Modules\Client\Models\ClientModel;

it('loads app config from Core module', function () {
    $this->assertNotNull(config('app.name'));
});

it('loads auth clients provider', function () {
    $this->assertSame(ClientModel::class, config('auth.providers.clients.model'));
});

it('loads auth barbers provider', function () {
    $this->assertSame(BarberModel::class, config('auth.providers.barbers.model'));
});

it('loads auth branches provider', function () {
    $this->assertSame(BranchModel::class, config('auth.providers.branches.model'));
});

it('loads auth admins provider', function () {
    $this->assertSame(AdminModel::class, config('auth.providers.admins.model'));
});
