<?php

// tests/Integration/Admin/Database/Factories/AdminFactoryTest.php
declare(strict_types=1);

use Modules\Admin\Database\Factories\AdminFactory;

it('creates an admin with translatable name', function () {
    $admin = AdminFactory::new()->create();

    expect($admin->getTranslations('name'))->toBeArray();
    expect($admin->getTranslation('name', 'en'))->not->toBeEmpty();
    expect($admin->getTranslation('name', 'ar'))->not->toBeEmpty();
    $this->assertDatabaseHas('admins', ['email' => $admin->email]);
});
