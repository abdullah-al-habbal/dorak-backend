<?php
// tests/Unit/Branch/Models/BranchModelTest.php
declare(strict_types=1);

use Filament\Panel;
use Modules\Branch\Models\BranchModel;

it('stores translatable name', function () {
    $branch = new BranchModel([
        'name' => ['en' => 'Downtown', 'ar' => 'وسط المدينة'],
        'email' => 'branch@example.com',
    ]);

    expect($branch->getTranslation('name', 'en'))->toBe('Downtown');
    expect($branch->getTranslation('name', 'ar'))->toBe('وسط المدينة');
});

it('can access branch panel', function () {
    $branch = new BranchModel();
    $panel = Panel::make()->id('branch');

    expect($branch->canAccessPanel($panel))->toBeTrue();
});

it('cannot access admin panel', function () {
    $branch = new BranchModel();
    $panel = Panel::make()->id('admin');

    expect($branch->canAccessPanel($panel))->toBeFalse();
});
