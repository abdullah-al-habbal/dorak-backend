<?php
declare(strict_types=1);

namespace Modules\Core\Eloquent\Resolvers;

use Illuminate\Support\Facades\DB;
use Throwable;

class HealthCheckResolver
{
    public function checkDatabase(): array
    {
        $start = microtime(true);

        try {
            DB::connection()->getPdo();
            return [
                'status'     => 'ok',
                'driver'     => (string) config('database.default'),
                'latency_ms' => round((microtime(true) - $start) * 1000, 2),
            ];
        } catch (Throwable) {
            return [
                'status' => 'fail',
                'driver' => (string) config('database.default'),
            ];
        }
    }
}
