<?php
// modules/Brand/Providers/BrandServiceProvider.php
declare(strict_types=1);

namespace Modules\Brand\Providers;

use Illuminate\Support\ServiceProvider;

final class BrandServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }
}
