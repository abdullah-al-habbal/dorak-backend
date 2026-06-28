<?php
// tests/Unit/Barber/Models/BarberModelTest.php
declare(strict_types=1);

use Filament\Panel;
use Modules\Barber\Models\BarberModel;

it('stores translatable name', function () {
    $barber = new BarberModel([
        'name' => ['en' => 'John', 'ar' => 'جون'],
        'email' => 'john@example.com',
    ]);

    expect($barber->getTranslation('name', 'en'))->toBe('John');
    expect($barber->getTranslation('name', 'ar'))->toBe('جون');
});

it('can access barber panel', function () {
    $barber = new BarberModel();
    $panel = Panel::make()->id('barber');

    expect($barber->canAccessPanel($panel))->toBeTrue();
});

it('cannot access admin panel', function () {
    $barber = new BarberModel();
    $panel = Panel::make()->id('admin');

    expect($barber->canAccessPanel($panel))->toBeFalse();
});

it('cannot access branch panel', function () {
    $barber = new BarberModel();
    $panel = Panel::make()->id('branch');

    expect($barber->canAccessPanel($panel))->toBeFalse();
});
