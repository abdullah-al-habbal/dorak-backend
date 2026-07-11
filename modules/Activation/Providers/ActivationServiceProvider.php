<?php
declare(strict_types=1);

namespace Modules\Activation\Providers;

use Modules\Activation\Models\ActivationLogModel;
use Modules\Activation\Observers\ActivationLogObserver;
use Modules\Core\Providers\BaseModuleServiceProvider;

final class ActivationServiceProvider extends BaseModuleServiceProvider
{
    public function __construct($app)
    {
        parent::__construct($app);
        $this->moduleDir = dirname(__DIR__);
        $this->moduleNamespace = __NAMESPACE__;
        $this->moduleName = 'activation';
    }

    protected function afterBoot(): void
    {
        ActivationLogModel::observe(ActivationLogObserver::class);
    }
}
