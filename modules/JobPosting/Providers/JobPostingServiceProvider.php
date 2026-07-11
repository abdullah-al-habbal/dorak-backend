<?php

declare(strict_types=1);

namespace Modules\JobPosting\Providers;

use Modules\Core\Providers\BaseModuleServiceProvider;

final class JobPostingServiceProvider extends BaseModuleServiceProvider
{
    public function __construct($app)
    {
        parent::__construct($app);
        $this->moduleDir = dirname(__DIR__);
        $this->moduleNamespace = __NAMESPACE__;
        $this->moduleName = 'jobposting';
    }
}
