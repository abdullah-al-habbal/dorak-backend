<?php
// bootstrap/providers.php

declare(strict_types=1);

use Modules\Core\Providers\ApplicationServiceProvider;
use Illuminate\Filesystem\FilesystemServiceProvider;
use Illuminate\View\ViewServiceProvider;
use Illuminate\Hashing\HashServiceProvider;

return [
    FilesystemServiceProvider::class,
    ViewServiceProvider::class,
    HashServiceProvider::class,
    \Modules\Client\Providers\ClientServiceProvider::class,
    ApplicationServiceProvider::class,
];