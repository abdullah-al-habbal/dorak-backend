<?php
// modules/Client/Providers/ClientServiceProvider.php
declare(strict_types=1);

namespace Modules\Client\Providers;

use Illuminate\Support\ServiceProvider;

final class ClientServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }
}
