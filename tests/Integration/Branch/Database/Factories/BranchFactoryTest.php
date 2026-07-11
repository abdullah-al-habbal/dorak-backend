<?php

// tests/Integration/Branch/Database/Factories/BranchFactoryTest.php
declare(strict_types=1);

use Modules\Branch\Database\Factories\BranchFactory;

it('creates a branch with translatable name', function () {
    $branch = BranchFactory::new()->create();

    expect($branch->getTranslations('name'))->toBeArray();
    expect($branch->getTranslation('name', 'en'))->not->toBeEmpty();
    expect($branch->getTranslation('name', 'ar'))->not->toBeEmpty();
    $this->assertDatabaseHas('branches', ['email' => $branch->email]);
});
