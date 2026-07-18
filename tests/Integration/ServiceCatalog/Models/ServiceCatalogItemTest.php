<?php

declare(strict_types=1);

use Modules\ServiceCatalog\Models\ServiceCatalogCategoryModel;
use Modules\ServiceCatalog\Models\ServiceCatalogItemModel;

it('creates item with category', function () {
    $category = ServiceCatalogCategoryModel::factory()->create();
    $item = ServiceCatalogItemModel::factory()->create([
        'category_id' => $category->id,
    ]);

    expect($item->category_id)->toBe($category->id);
    expect($item->category->id)->toBe($category->id);
});

it('cascades delete when category is deleted', function () {
    $category = ServiceCatalogCategoryModel::factory()->create();
    $item = ServiceCatalogItemModel::factory()->create([
        'category_id' => $category->id,
    ]);

    $category->forceDelete();

    expect(ServiceCatalogItemModel::find($item->id))->toBeNull();
});

it('stores and retrieves price range as value object', function () {
    $item = ServiceCatalogItemModel::factory()->create([
        'price_range' => ['min' => 10.0, 'max' => 50.0, 'currency' => 'SAR'],
    ]);

    expect($item->price_range)->toBeInstanceOf(\Modules\ServiceCatalog\ValuesObjects\PriceRangeValueObject::class);
    expect($item->price_range->min())->toBe(10.0);
    expect($item->price_range->max())->toBe(50.0);
    expect($item->price_range->currency())->toBe('SAR');
});

it('tags can be attached to item', function () {
    $item = ServiceCatalogItemModel::factory()->create();
    $tag = \Modules\ServiceCatalog\Models\ServiceCatalogItemTagModel::factory()->create();

    $item->tags()->attach($tag);

    expect($item->tags)->toHaveCount(1);
    expect($item->tags->first()->id)->toBe($tag->id);
});
