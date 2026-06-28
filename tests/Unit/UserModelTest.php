<?php
// tests/Unit/UserModelTest.php
declare(strict_types=1);

use Modules\Core\Models\UserModel;

it('stores translatable name', function () {
    $user = new UserModel([
        'name' => ['en' => 'John', 'ar' => 'جون'],
        'email' => 'john@example.com',
    ]);

    expect($user->getTranslation('name', 'en'))->toBe('John');
    expect($user->getTranslation('name', 'ar'))->toBe('جون');
});
