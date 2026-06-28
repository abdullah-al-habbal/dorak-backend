<?php
// modules/Core/Providers/ApplicationServiceProvider.php
declare(strict_types=1);

namespace Modules\Core\Providers;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

final class ApplicationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->loadConfig();
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../Lang', 'core');
        $this->loadRoutes();
    }

    private function loadRoutes(): void
    {
        $this->loadApiV1Routes();
    }

    private function loadApiV1Routes(): void
    {
        Route::prefix('api')
            ->middleware('api')
            ->group(__DIR__ . '/../Routes/Api/V1/api_v1_routes.php');
    }

    private function loadConfig(): void
    {
        $config = $this->app->make(ConfigRepository::class);
        $path   = __DIR__ . '/../Config';

        foreach (glob($path . '/*.php') as $file) {
            $key = basename($file, '.php');
            $config->set($key, require $file);
        }
    }
}
