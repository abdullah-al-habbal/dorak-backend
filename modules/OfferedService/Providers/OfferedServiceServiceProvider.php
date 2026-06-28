<?php
// modules/OfferedService/Providers/OfferedServiceServiceProvider.php
declare(strict_types=1);

namespace Modules\OfferedService\Providers;

use Illuminate\Support\ServiceProvider;

final class OfferedServiceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }
}
