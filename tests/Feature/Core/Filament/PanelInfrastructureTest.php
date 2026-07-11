<?php

// tests/Feature/Core/Filament/PanelInfrastructureTest.php
declare(strict_types=1);

use Filament\PanelRegistry;
use Modules\Activation\Models\ActivationLogModel;
use Modules\Admin\Models\AdminModel;
use Modules\Barber\Models\BarberModel;
use Modules\Branch\Models\BranchModel;
use Modules\Currency\Models\CurrencyModel;
use Modules\Currency\Models\ExchangeRateModel;
use Modules\Language\Models\LanguageModel;

beforeEach(function () {
    $this->registry = app(PanelRegistry::class);
});

// ─── Panel registration ─────────────────────────────────────────

it('registers admin panel', function () {
    $panel = $this->registry->get('admin');
    expect($panel->getId())->toBe('admin');
    expect($panel->getPath())->toBe('admin');
});

it('registers barber panel', function () {
    $panel = $this->registry->get('barber');
    expect($panel->getId())->toBe('barber');
    expect($panel->getPath())->toBe('barber');
});

it('registers branch panel', function () {
    $panel = $this->registry->get('branch');
    expect($panel->getId())->toBe('branch');
    expect($panel->getPath())->toBe('branch');
});

// ─── Panel guards ────────────────────────────────────────────────

it('admin panel uses admin guard', function () {
    expect($this->registry->get('admin')->getAuthGuard())->toBe('admin');
});

it('barber panel uses barber_dashboard guard', function () {
    expect($this->registry->get('barber')->getAuthGuard())->toBe('barber_dashboard');
});

it('branch panel uses branch guard', function () {
    expect($this->registry->get('branch')->getAuthGuard())->toBe('branch');
});

// ─── Admin panel is default ──────────────────────────────────────

it('admin panel is default', function () {
    expect($this->registry->get('admin')->isDefault())->toBeTrue();
});

// ─── Resource discovery ──────────────────────────────────────────

it('discovers all admin resources', function () {
    $resources = $this->registry->get('admin')->getResources();
    $classes = array_map(fn ($r) => class_basename($r), $resources);

    expect($classes)->toContain('AdminUserResource');
    expect($classes)->toContain('BarberResource');
    expect($classes)->toContain('BranchResource');
    expect($classes)->toContain('ClientResource');
    expect($classes)->toContain('LanguageResource');
    expect($classes)->toContain('CurrencyResource');
    expect($classes)->toContain('ExchangeRateResource');
    expect($classes)->toContain('ActivationLogResource');
    expect($classes)->toContain('BanResource');
    expect($classes)->toContain('BrandResource');
    expect($classes)->toContain('PreferenceResource');
    expect($classes)->toContain('BarberAffiliationResource');
    expect($classes)->toContain('OfferedServiceResource');
    expect($classes)->toContain('ChairResource');
    expect($classes)->toContain('BookingResource');
    expect($classes)->toContain('ReviewResource');
    expect($classes)->toContain('JobPostingResource');
    expect($classes)->toContain('ApplicationResource');
});

it('discovers barber panel profile resource', function () {
    $resources = $this->registry->get('barber')->getResources();
    $classes = array_map(fn ($r) => class_basename($r), $resources);

    expect($classes)->toContain('ProfileResource');
});

it('discovers branch panel profile resource', function () {
    $resources = $this->registry->get('branch')->getResources();
    $classes = array_map(fn ($r) => class_basename($r), $resources);

    expect($classes)->toContain('ProfileResource');
});

// ─── Panel HTTP accessibility ────────────────────────────────────

it('admin panel login page loads', function () {
    $this->get('/admin/login')->assertStatus(200);
});

it('barber panel login page loads', function () {
    $this->get('/barber/login')->assertStatus(200);
});

it('branch panel login page loads', function () {
    $this->get('/branch/login')->assertStatus(200);
});

// ─── Authenticated admin panel access ────────────────────────────

it('admin panel home redirects to first resource', function () {
    $admin = AdminModel::factory()->create();
    $this->actingAs($admin, 'admin');

    $this->get('/admin')->assertRedirect();
});

it('admin can access admin user resource', function () {
    $admin = AdminModel::factory()->create();
    $this->actingAs($admin, 'admin');

    $this->get('/admin/admin-users')->assertStatus(200);
});

it('admin can view admin user detail', function () {
    $admin = AdminModel::factory()->create();
    $target = AdminModel::factory()->create();
    $this->actingAs($admin, 'admin');

    $this->get("/admin/admin-users/{$target->id}")->assertStatus(200);
});

it('admin can access barber resource', function () {
    $admin = AdminModel::factory()->create();
    $this->actingAs($admin, 'admin');

    $this->get('/admin/barbers')->assertStatus(200);
});

it('admin can access branch resource', function () {
    $admin = AdminModel::factory()->create();
    $this->actingAs($admin, 'admin');

    $this->get('/admin/branches')->assertStatus(200);
});

it('admin can access client resource', function () {
    $admin = AdminModel::factory()->create();
    $this->actingAs($admin, 'admin');

    $this->get('/admin/clients')->assertStatus(200);
});

it('admin can access language resource', function () {
    $admin = AdminModel::factory()->create();
    LanguageModel::factory()->create();
    $this->actingAs($admin, 'admin');

    $this->get('/admin/languages')->assertStatus(200);
});

it('admin can access currency resource', function () {
    $admin = AdminModel::factory()->create();
    CurrencyModel::factory()->create();
    $this->actingAs($admin, 'admin');

    $this->get('/admin/currencies')->assertStatus(200);
});

it('admin can access exchange rate resource', function () {
    $admin = AdminModel::factory()->create();
    $currency = CurrencyModel::factory()->create();
    ExchangeRateModel::factory()->create([
        'from_currency_id' => $currency->id,
        'to_currency_id' => $currency->id,
    ]);
    $this->actingAs($admin, 'admin');

    $this->get('/admin/exchange-rates')->assertStatus(200);
});

it('admin can access activation log resource', function () {
    $admin = AdminModel::factory()->create();
    ActivationLogModel::factory()->create();
    $this->actingAs($admin, 'admin');

    $this->get('/admin/activation-logs')->assertStatus(200);
});

it('admin can access ban resource', function () {
    $admin = AdminModel::factory()->create();
    $this->actingAs($admin, 'admin');

    $this->get('/admin/bans')->assertStatus(200);
});

it('admin can access brand resource', function () {
    $admin = AdminModel::factory()->create();
    $this->actingAs($admin, 'admin');

    $this->get('/admin/brands')->assertStatus(200);
});

it('admin can access preference resource', function () {
    $admin = AdminModel::factory()->create();
    $this->actingAs($admin, 'admin');

    $this->get('/admin/preferences')->assertStatus(200);
});

it('admin can access barber affiliation resource', function () {
    $admin = AdminModel::factory()->create();
    $this->actingAs($admin, 'admin');

    $this->get('/admin/barber-affiliations')->assertStatus(200);
});

it('admin can access offered service resource', function () {
    $admin = AdminModel::factory()->create();
    $this->actingAs($admin, 'admin');

    $this->get('/admin/offered-services')->assertStatus(200);
});

it('admin can access chair resource', function () {
    $admin = AdminModel::factory()->create();
    $this->actingAs($admin, 'admin');

    $this->get('/admin/chairs')->assertStatus(200);
});

it('admin can access booking resource', function () {
    $admin = AdminModel::factory()->create();
    $this->actingAs($admin, 'admin');

    $this->get('/admin/bookings')->assertStatus(200);
});

it('admin can access review resource', function () {
    $admin = AdminModel::factory()->create();
    $this->actingAs($admin, 'admin');

    $this->get('/admin/reviews')->assertStatus(200);
});

it('admin can access job posting resource', function () {
    $admin = AdminModel::factory()->create();
    $this->actingAs($admin, 'admin');

    $this->get('/admin/job-postings')->assertStatus(200);
});

it('admin can access application resource', function () {
    $admin = AdminModel::factory()->create();
    $this->actingAs($admin, 'admin');

    $this->get('/admin/applications')->assertStatus(200);
});

// ─── Authenticated barber panel access ───────────────────────────

it('barber panel home redirects to profile resource', function () {
    $barber = BarberModel::factory()->create();
    $this->actingAs($barber, 'barber_dashboard');

    $this->get('/barber')->assertRedirect();
});

it('barber can access own profile', function () {
    $barber = BarberModel::factory()->create();
    $this->actingAs($barber, 'barber_dashboard');

    $this->get('/barber/profile')->assertStatus(200);
});

it('barber sees own profile scoped', function () {
    BarberModel::factory()->create(); // another barber
    $barber = BarberModel::factory()->create();
    $this->actingAs($barber, 'barber_dashboard');

    $response = $this->get('/barber/profile');

    $response->assertStatus(200);
    $response->assertSee($barber->email);
});

// ─── Authenticated branch panel access ───────────────────────────

it('branch panel home redirects to profile resource', function () {
    $branch = BranchModel::factory()->create();
    $this->actingAs($branch, 'branch');

    $this->get('/branch')->assertRedirect();
});

it('branch can access own profile', function () {
    $branch = BranchModel::factory()->create();
    $this->actingAs($branch, 'branch');

    $this->get('/branch/profile')->assertStatus(200);
});

it('branch sees own profile scoped', function () {
    BranchModel::factory()->create(); // another branch
    $branch = BranchModel::factory()->create();
    $this->actingAs($branch, 'branch');

    $response = $this->get('/branch/profile');
    $response->assertStatus(200);
    $response->assertSee($branch->email);
});

// ─── Authorization: wrong guard redirected ───────────────────────

it('admin redirected from barber panel', function () {
    $admin = AdminModel::factory()->create();
    $this->actingAs($admin, 'admin');

    $this->get('/barber')->assertRedirect();
});

it('barber redirected from admin panel', function () {
    $barber = BarberModel::factory()->create();
    $this->actingAs($barber, 'barber_dashboard');

    $this->get('/admin')->assertRedirect();
});

it('branch redirected from admin panel', function () {
    $branch = BranchModel::factory()->create();
    $this->actingAs($branch, 'branch');

    $this->get('/admin')->assertRedirect();
});

// ─── Unauthenticated access redirects to login ───────────────────

it('unauthenticated access to admin redirects', function () {
    $this->get('/admin')->assertRedirect('/admin/login');
});

it('unauthenticated access to barber redirects', function () {
    $this->get('/barber')->assertRedirect('/barber/login');
});

it('unauthenticated access to branch redirects', function () {
    $this->get('/branch')->assertRedirect('/branch/login');
});
