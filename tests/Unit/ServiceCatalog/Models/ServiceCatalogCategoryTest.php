<?php

declare(strict_types=1);

use Illuminate\Database\QueryException;
use Modules\ServiceCatalog\Models\ServiceCatalogCategoryModel;

it('creates a category with translatable name', function () {
    $category = ServiceCatalogCategoryModel::factory()->create([
        'name' => ['en' => 'Haircuts', 'ar' => 'قصات شعر'],
    ]);

    expect($category->getTranslations('name'))->toBe(['en' => 'Haircuts', 'ar' => 'قصات شعر']);
});

it('is active by default', function () {
    $category = ServiceCatalogCategoryModel::factory()->create();

    expect($category->is_active)->toBeTrue();
});

it('can be inactive', function () {
    $category = ServiceCatalogCategoryModel::factory()->inactive()->create();

    expect($category->is_active)->toBeFalse();
});

it('has unique slug', function () {
    ServiceCatalogCategoryModel::factory()->create(['slug' => 'haircuts']);

    expect(fn () => ServiceCatalogCategoryModel::factory()->create(['slug' => 'haircuts']))
        ->toThrow(QueryException::class);
});
