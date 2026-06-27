<?php
declare(strict_types=1);

use Illuminate\Support\Str;
return [
    'driver' => env('SESSION_DRIVER'),
    'lifetime' => (int) env('SESSION_LIFETIME'),
    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE'),
    'encrypt' => env('SESSION_ENCRYPT'),
    'files' => storage_path('framework/sessions'),
    'connection' => env('SESSION_CONNECTION'),
    'table' => env('SESSION_TABLE'),
    'store' => env('SESSION_STORE'),
    'lottery' => [2, 100],
    'cookie' => env('SESSION_COOKIE'),
    'path' => env('SESSION_PATH'),
    'domain' => env('SESSION_DOMAIN'),
    'secure' => env('SESSION_SECURE_COOKIE'),
    'http_only' => env('SESSION_HTTP_ONLY'),
    'same_site' => env('SESSION_SAME_SITE'),
    'partitioned' => env('SESSION_PARTITIONED_COOKIE'),
    'serialization' => 'json',
];
