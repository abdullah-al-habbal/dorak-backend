<?php

declare(strict_types=1);

namespace Modules\Core\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

abstract class BaseModuleServiceProvider extends ServiceProvider
{
    protected string $moduleDir;
    protected string $moduleNamespace;
    protected string $moduleName;

    protected array $webMiddleware = ['web'];

    public function register(): void {}

    public function boot(): void
    {
        $this->loadConfig();
        $this->loadConsoleRoutes();
        $this->loadRoutes();
        $this->loadMigrations();
        $this->loadTranslations();
        $this->loadViews();
        $this->afterBoot();
    }

    protected function afterBoot(): void {}

    protected function loadConfig(): void
    {
        $path = $this->moduleDir . '/Config';
        if (File::isDirectory($path)) {
            foreach (File::files($path) as $file) {
                if ($file->getExtension() === 'php') {
                    $key = $this->moduleName . '.' . $file->getFilenameWithoutExtension();
                    $this->mergeConfigFrom($file->getPathname(), $key);
                }
            }
        }
    }

    protected function loadConsoleRoutes(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $path = $this->moduleDir . '/Routes/Console';
        if (File::isDirectory($path)) {
            foreach (File::files($path) as $file) {
                if ($file->getExtension() === 'php') {
                    $this->loadRoutesFrom($file->getPathname());
                }
            }
        }
    }

    protected function loadRoutes(): void
    {
        $this->loadWebRoutes();
        $this->loadApiRoutes();
    }

    protected function loadWebRoutes(): void
    {
        $path = $this->moduleDir . '/Routes/Web';
        if (File::isDirectory($path)) {
            foreach (File::files($path) as $file) {
                if ($file->getExtension() === 'php') {
                    Route::middleware($this->webMiddleware)
                        ->group($file->getPathname());
                }
            }
        }
    }

    protected function loadApiRoutes(): void
    {
        $this->loadApiV1Routes();
    }

    protected function loadApiV1Routes(): void
    {
        $path = $this->moduleDir . '/Routes/Api/V1';
        if (File::isDirectory($path)) {
            foreach (File::files($path) as $file) {
                if ($file->getExtension() === 'php') {
                    Route::prefix('api/v1')
                        ->name('api.v1.')
                        ->middleware(['api'])
                        ->group($file->getPathname());
                }
            }
        }
    }

    protected function loadMigrations(): void
    {
        $path = $this->moduleDir . '/Database/Migrations';
        if (File::isDirectory($path)) {
            $this->loadMigrationsFrom($path);
        }
    }

    protected function loadTranslations(): void
    {
        $path = $this->moduleDir . '/Lang';
        if (File::isDirectory($path)) {
            $this->loadTranslationsFrom($path, $this->moduleName);
        }
    }

    protected function loadViews(): void
    {
        $path = $this->moduleDir . '/Resources/views';
        if (File::isDirectory($path)) {
            $this->loadViewsFrom($path, $this->moduleName);
        }
    }
}
