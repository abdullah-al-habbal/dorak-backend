<?php

// tests/Unit/Client/Models/ClientModelTest.php
declare(strict_types=1);

use Filament\Panel;
use Modules\Client\Models\ClientModel;

it('stores translatable name', function () {
    $client = new ClientModel([
        'name' => ['en' => 'John', 'ar' => 'جون'],
        'email' => 'john@example.com',
    ]);

    expect($client->getTranslation('name', 'en'))->toBe('John');
    expect($client->getTranslation('name', 'ar'))->toBe('جون');
});

it('cannot access barber panel when not a barber', function () {
    $client = new ClientModel;
    $panel = Panel::make()->id('barber');

    expect($client->canAccessPanel($panel))->toBeFalse();
});
