<?php

declare(strict_types=1);

use Modules\Admin\Models\AdminModel;
use Modules\Brand\Models\BrandModel;

beforeEach(function () {
    $this->admin = AdminModel::factory()->create();
    $this->actingAs($this->admin, 'admin');
});

it('loads create page', function () {
    $this->get('/admin/brands/create')->assertStatus(200);
});

it('loads view page', function () {
    $brand = BrandModel::factory()->create();

    $this->get("/admin/brands/{$brand->id}")->assertStatus(200);
});

it('loads edit page', function () {
    $brand = BrandModel::factory()->create();

    $this->get("/admin/brands/{$brand->id}/edit")->assertStatus(200);
});

it('loads list page', function () {
    BrandModel::factory()->count(3)->create();

    $this->get('/admin/brands')->assertStatus(200);
});
