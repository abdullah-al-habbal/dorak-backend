<?php

declare(strict_types=1);

use Modules\Core\Providers\ApplicationServiceProvider;
use Illuminate\Filesystem\FilesystemServiceProvider;
use Illuminate\View\ViewServiceProvider;

return [
    FilesystemServiceProvider::class,
    ViewServiceProvider::class,
    ApplicationServiceProvider::class,
];