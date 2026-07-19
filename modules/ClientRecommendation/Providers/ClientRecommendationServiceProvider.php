<?php

declare(strict_types=1);

namespace Modules\ClientRecommendation\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Modules\Booking\Models\BookingModel;
use Modules\ClientInteraction\Models\ClientFavoriteModel;
use Modules\ClientRecommendation\Console\Commands\RecomputeClientVectorsCommand;
use Modules\ClientRecommendation\Observers\BookingCompletedObserver;
use Modules\ClientRecommendation\Observers\ClientFavoriteObserver;
use Modules\Core\Providers\BaseModuleServiceProvider;

final class ClientRecommendationServiceProvider extends BaseModuleServiceProvider
{
    public function __construct($app)
    {
        parent::__construct($app);
        $this->moduleDir = dirname(__DIR__);
        $this->moduleNamespace = __NAMESPACE__;
        $this->moduleName = 'clientrecommendation';
    }

    public function register(): void
    {
        $this->commands([
            RecomputeClientVectorsCommand::class,
        ]);
    }

    protected function afterBoot(): void
    {
        ClientFavoriteModel::observe(ClientFavoriteObserver::class);
        BookingModel::observe(BookingCompletedObserver::class);

        $this->app->afterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command('recommend:recompute-vectors')
                ->dailyAt('02:00')
                ->withoutOverlapping()
                ->runInBackground();
        });
    }
}
