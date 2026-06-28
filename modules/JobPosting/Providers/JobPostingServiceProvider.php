<?php
// modules/JobPosting/Providers/JobPostingServiceProvider.php
declare(strict_types=1);

namespace Modules\JobPosting\Providers;

use Illuminate\Support\ServiceProvider;

final class JobPostingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }
}
