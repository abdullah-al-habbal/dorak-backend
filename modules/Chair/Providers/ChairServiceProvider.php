<?php

declare(strict_types=1);

namespace Modules\Chair\Providers;

use Modules\Chair\Models\ChairModel;
use Modules\Chair\Observers\ChairObserver;
use Modules\Core\Providers\BaseModuleServiceProvider;

final class ChairServiceProvider extends BaseModuleServiceProvider
{
    public function __construct($app)
    {
        parent::__construct($app);
        $this->moduleDir = dirname(__DIR__);
        $this->moduleNamespace = __NAMESPACE__;
        $this->moduleName = 'chair';
    }

    protected function afterBoot(): void
    {
        ChairModel::observe(ChairObserver::class);
    }
}
