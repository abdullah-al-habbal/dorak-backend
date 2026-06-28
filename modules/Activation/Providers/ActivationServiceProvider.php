<?php
// modules/Activation/Providers/ActivationServiceProvider.php
declare(strict_types=1);

namespace Modules\Activation\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Activation\Models\ActivationLogModel;
use Modules\Activation\Observers\ActivationLogObserver;

final class ActivationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        ActivationLogModel::observe(ActivationLogObserver::class);
    }
}
