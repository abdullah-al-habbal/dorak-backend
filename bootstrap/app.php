<?php
declare(strict_types=1);

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Modules\Core\Http\Middleware\AssignRequestUuid;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        health: '/up',
    )
    ->withConfig(function (ConfigRepository $config): void {
        $config->set([], true);

        $path = __DIR__ . '/../modules/Core/Config';

        foreach (glob($path . '/*.php') as $file) {
            $key = basename($file, '.php');
            $config->set($key, require $file);
        }
    })
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->prepend(AssignRequestUuid::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
