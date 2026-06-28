<?php
// modules/Branch/Providers/BranchServiceProvider.php
declare(strict_types=1);

namespace Modules\Branch\Providers;

use Illuminate\Support\ServiceProvider;

final class BranchServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }
}
