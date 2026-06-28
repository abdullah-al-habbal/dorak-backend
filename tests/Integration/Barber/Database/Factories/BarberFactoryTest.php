<?php
// tests/Integration/Barber/Database/Factories/BarberFactoryTest.php
declare(strict_types=1);

use Modules\Barber\Database\Factories\BarberFactory;

it('creates a barber with translatable name', function () {
    $barber = BarberFactory::new()->create();

    expect($barber->getTranslations('name'))->toBeArray();
    expect($barber->getTranslation('name', 'en'))->not->toBeEmpty();
    expect($barber->getTranslation('name', 'ar'))->not->toBeEmpty();
    $this->assertDatabaseHas('barbers', ['email' => $barber->email]);
});

it('creates a freelancer barber', function () {
    $barber = BarberFactory::new()->freelancer()->create();

    expect($barber->is_freelancer)->toBeTrue();
    expect($barber->client_id)->toBeNull();
});
