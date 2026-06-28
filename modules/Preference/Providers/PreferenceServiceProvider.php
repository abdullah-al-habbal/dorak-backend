<?php
// modules/Preference/Providers/PreferenceServiceProvider.php
declare(strict_types=1);

namespace Modules\Preference\Providers;

use Illuminate\Support\ServiceProvider;

final class PreferenceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }
}
