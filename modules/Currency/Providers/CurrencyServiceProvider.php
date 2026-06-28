<?php
// modules/Currency/Providers/CurrencyServiceProvider.php
declare(strict_types=1);

namespace Modules\Currency\Providers;

use Illuminate\Support\ServiceProvider;

final class CurrencyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }
}
