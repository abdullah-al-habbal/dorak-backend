<?php
declare(strict_types=1);

namespace Modules\Core\Providers;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Route;
use Modules\Barber\Models\BarberModel;
use Modules\Branch\Models\BranchModel;
use Modules\Brand\Models\BrandModel;
use Modules\Client\Models\ClientModel;

final class ApplicationServiceProvider extends BaseModuleServiceProvider
{
    public function __construct($app)
    {
        parent::__construct($app);
        $this->moduleDir = dirname(__DIR__);
        $this->moduleNamespace = __NAMESPACE__;
        $this->moduleName = 'core';
    }

    public function register(): void
    {
        $this->loadAppConfig();
    }

    protected function loadRoutes(): void {}

    protected function afterBoot(): void
    {
        $this->registerMorphMap();
        $this->registerFactoryResolution();
        $this->loadAppRoutes();
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

    private function loadAppRoutes(): void
    {
        Route::prefix('api')
            ->middleware('api')
            ->group(__DIR__ . '/../Routes/Api/V1/api_v1_routes.php');
    }

    private function loadAppConfig(): void
    {
        $config = $this->app->make(ConfigRepository::class);
        $path   = __DIR__ . '/../Config';

        foreach (glob($path . '/*.php') as $file) {
            $key = basename($file, '.php');
            $config->set($key, require $file);
        }
    }
}
