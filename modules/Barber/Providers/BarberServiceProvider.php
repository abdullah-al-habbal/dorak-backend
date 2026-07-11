<?php
declare(strict_types=1);

namespace Modules\Barber\Providers;

use Modules\Core\Providers\BaseModuleServiceProvider;

final class BarberServiceProvider extends BaseModuleServiceProvider
{
    public function __construct($app)
    {
        parent::__construct($app);
        $this->moduleDir = dirname(__DIR__);
        $this->moduleNamespace = __NAMESPACE__;
        $this->moduleName = 'barber';
    }
}
