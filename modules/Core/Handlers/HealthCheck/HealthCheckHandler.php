<?php

declare(strict_types=1);

namespace Modules\Core\Handlers\HealthCheck;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\Core\Eloquent\Resolvers\HealthCheckResolver;
use Throwable;

class HealthCheckHandler
{
    public function __construct(
        private readonly HealthCheckResolver $resolver,
    ) {}

    public function handle(): array
    {
        $checks = [
            'app' => $this->checkApp(),
            'database' => $this->resolver->checkDatabase(),
            'cache' => $this->checkCache(),
            'php' => $this->checkPhp(),
            'queue' => $this->checkQueue(),
        ];

        return [
            'status' => $this->resolveOverallStatus($checks),
            'timestamp' => now()->toIso8601String(),
            'checks' => $checks,
        ];
    }

    private function checkApp(): array
    {
        return [
            'status' => 'ok',
            'environment' => (string) config('app.env'),
            'debug_mode' => (bool) config('app.debug'),
            'timezone' => (string) config('app.timezone'),
            'locale' => (string) config('app.locale'),
        ];
    }

    private function checkCache(): array
    {
        $key = 'health:probe:'.uniqid('', true);

        try {
            Cache::put($key, true, 10);
            $hit = Cache::get($key) === true;
            Cache::forget($key);

            return [
                'status' => $hit ? 'ok' : 'fail',
                'driver' => (string) config('cache.default'),
            ];
        } catch (Throwable) {
            return [
                'status' => 'fail',
                'driver' => (string) config('cache.default'),
            ];
        }
    }

    private function checkPhp(): array
    {
        $required = ['pdo', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json', 'bcmath'];
        $loaded = array_map('strtolower', get_loaded_extensions());
        $extensions = array_combine(
            $required,
            array_map(fn (string $ext): bool => in_array($ext, $loaded, true), $required),
        );

        return [
            'status' => ! in_array(false, $extensions, true) ? 'ok' : 'warn',
            'version' => PHP_VERSION,
            'extensions' => $extensions,
        ];
    }

    private function checkQueue(): array
    {
        $driver = (string) config('queue.default');

        try {
            if ($driver === 'database') {
                $table = (string) config('queue.connections.database.table', 'jobs');
                DB::table($table)->limit(1)->count();
            }

            return ['status' => 'ok', 'driver' => $driver];
        } catch (Throwable) {
            return ['status' => 'fail', 'driver' => $driver];
        }
    }

    private function resolveOverallStatus(array $checks): string
    {
        if ($checks['database']['status'] === 'fail') {
            return 'down';
        }

        foreach ($checks as $check) {
            if (in_array($check['status'], ['fail', 'warn'], true)) {
                return 'degraded';
            }
        }

        return 'ok';
    }
}
