<?php
// modules/Barber/Providers/BarberServiceProvider.php
declare(strict_types=1);

namespace Modules\Barber\Providers;

use Illuminate\Support\ServiceProvider;

final class BarberServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }
}
