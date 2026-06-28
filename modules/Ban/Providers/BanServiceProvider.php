<?php
// modules/Ban/Providers/BanServiceProvider.php
declare(strict_types=1);

namespace Modules\Ban\Providers;

use Illuminate\Support\ServiceProvider;

final class BanServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }
}
