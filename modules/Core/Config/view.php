<?php

declare(strict_types=1);

// modules/Core/Config/view.php
//
// This project has no top-level `resources/views/` directory (module-per-domain
// layout). Without this override, the framework default `view.paths`
// (resource_path('views')) points at a non-existent dir and `view:cache` /
// `php artisan optimize` throw DirectoryNotFoundException. Real Blade views live
// under `modules/*/Resources/views` and are registered as namespaced hints, but
// they must also appear in `paths` for `view:cache` to compile them.

$moduleViewPaths = array_values(array_filter(
    glob(base_path('modules/*/Resources/views')) ?: [],
    'is_dir',
));

return [
    'paths' => $moduleViewPaths,

    'compiled' => env(
        'VIEW_COMPILED_PATH',
        storage_path('framework/views'),
    ),
];
