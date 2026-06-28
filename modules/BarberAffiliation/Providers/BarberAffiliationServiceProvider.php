<?php
// modules/BarberAffiliation/Providers/BarberAffiliationServiceProvider.php
declare(strict_types=1);

namespace Modules\BarberAffiliation\Providers;

use Illuminate\Support\ServiceProvider;

final class BarberAffiliationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }
}
