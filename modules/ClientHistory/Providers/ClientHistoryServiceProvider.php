<?php

declare(strict_types=1);

namespace Modules\ClientHistory\Providers;

use Modules\Booking\Models\BookingModel;
use Modules\ClientHistory\Observers\BookingCompletedObserver;
use Modules\Core\Providers\BaseModuleServiceProvider;

final class ClientHistoryServiceProvider extends BaseModuleServiceProvider
{
    public function __construct($app)
    {
        parent::__construct($app);
        $this->moduleDir = dirname(__DIR__);
        $this->moduleNamespace = __NAMESPACE__;
        $this->moduleName = 'clienthistory';
    }

    protected function afterBoot(): void
    {
        BookingModel::observe(BookingCompletedObserver::class);
    }
}
