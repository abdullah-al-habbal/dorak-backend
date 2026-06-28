<?php
// modules/Core/Providers/ApplicationServiceProvider.php
declare(strict_types=1);

namespace Modules\Core\Providers;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\Barber\Models\BarberModel;
use Modules\Branch\Models\BranchModel;
use Modules\Brand\Models\BrandModel;
use Modules\Client\Models\ClientModel;

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
        $this->registerMorphMap();
        $this->registerFactoryResolution();
        $this->loadRoutes();
    }

    private function registerMorphMap(): void
    {
        Relation::enforceMorphMap([
            'brand'  => BrandModel::class,
            'branch' => BranchModel::class,
            'barber' => BarberModel::class,
            'client' => ClientModel::class,
        ]);
    }

    private function registerFactoryResolution(): void
    {
        Factory::guessFactoryNamesUsing(function (string $modelName): string {
            return preg_replace(
                '/^Modules\\\\([^\\\\]+)\\\\Models\\\\(.+)Model$/',
                'Modules\\\\$1\\\\Database\\\\Factories\\\\$2Factory',
                $modelName
            );
        });
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
