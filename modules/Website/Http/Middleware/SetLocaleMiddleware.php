<?php

declare(strict_types=1);

namespace Modules\Website\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Vite;

final class SetLocaleMiddleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        $locale = $request->route('locale', 'ar');

        if (! in_array($locale, ['ar', 'en'], true)) {
            $locale = 'ar';
        }

        App::setLocale($locale);

        Vite::prefetch(concurrency: 3);

        $request->attributes->set('locale', $locale);
        $request->attributes->set('available_locales', ['ar', 'en']);

        return $next($request);
    }
}
