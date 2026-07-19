<?php

declare(strict_types=1);

namespace Modules\ClientRecommendation\Providers;

use Modules\Booking\Models\BookingModel;
use Modules\ClientInteraction\Models\ClientFavoriteModel;
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

    protected function afterBoot(): void
    {
        ClientFavoriteModel::observe(ClientFavoriteObserver::class);
        BookingModel::observe(BookingCompletedObserver::class);
    }
}
