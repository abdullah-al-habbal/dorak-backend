<?php
// modules/Review/Providers/ReviewServiceProvider.php
declare(strict_types=1);

namespace Modules\Review\Providers;

use Illuminate\Support\ServiceProvider;

final class ReviewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }
}
