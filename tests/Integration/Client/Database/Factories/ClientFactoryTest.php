<?php

// tests/Integration/Client/Database/Factories/ClientFactoryTest.php
declare(strict_types=1);

use Modules\Client\Database\Factories\ClientFactory;

it('creates a client with translatable name', function () {
    $client = ClientFactory::new()->create();

    expect($client->getTranslations('name'))->toBeArray();
    expect($client->getTranslation('name', 'en'))->not->toBeEmpty();
    expect($client->getTranslation('name', 'ar'))->not->toBeEmpty();
    $this->assertDatabaseHas('clients', ['email' => $client->email]);
});
