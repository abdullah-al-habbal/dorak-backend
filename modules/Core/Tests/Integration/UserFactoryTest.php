<?php

declare(strict_types=1);

use Modules\Core\Database\Factories\UserFactory;

uses(Modules\Core\Tests\TestCase::class);

it('creates a user with translatable name', function () {
    $user = UserFactory::new()->create();

    expect($user->name)->toBeArray();
    expect($user->getTranslation('name', 'en'))->not->toBeEmpty();
    expect($user->getTranslation('name', 'ar'))->not->toBeEmpty();
    $this->assertDatabaseHas('users', ['email' => $user->email]);
});
