<?php
// modules/Chair/Providers/ChairServiceProvider.php
declare(strict_types=1);

namespace Modules\Chair\Providers;

use Illuminate\Support\ServiceProvider;

final class ChairServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }
}
