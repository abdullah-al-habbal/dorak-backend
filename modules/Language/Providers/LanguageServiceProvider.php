<?php
// modules/Language/Providers/LanguageServiceProvider.php
declare(strict_types=1);

namespace Modules\Language\Providers;

use Illuminate\Support\ServiceProvider;

final class LanguageServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }
}
