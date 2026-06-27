<?php
declare(strict_types=1);

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

final class AssignRequestUuidMiddleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        $uuid = Str::uuid()->toString();
        $request->attributes->set('request_uuid', $uuid);
        $request->merge(['request_uuid' => $uuid]);

        return $next($request);
    }
}
