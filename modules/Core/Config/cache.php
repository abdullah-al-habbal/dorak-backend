<?php
declare(strict_types=1);
namespace Modules\Core\Config;

use Illuminate\Support\Str;
return [
    'default' => env('CACHE_STORE'),
    'stores' => [
        'array' => [
            'driver' => 'array',
            'serialize' => false,
        ],
        'database' => [
            'driver' => 'database',
            'connection' => env('DB_CACHE_CONNECTION'),
            'table' => env('DB_CACHE_TABLE'),
            'lock_connection' => env('DB_CACHE_LOCK_CONNECTION'),
            'lock_table' => env('DB_CACHE_LOCK_TABLE'),
        ],
        'file' => [
            'driver' => 'file',
            'path' => storage_path('framework/cache/data'),
            'lock_path' => storage_path('framework/cache/data'),
        ],
        'storage' => [
            'driver' => 'storage',
            'disk' => env('CACHE_STORAGE_DISK'),
            'path' => env('CACHE_STORAGE_PATH'),
        ],
        'memcached' => [
            'driver' => 'memcached',
            'persistent_id' => env('MEMCACHED_PERSISTENT_ID'),
            'sasl' => [env('MEMCACHED_USERNAME'), env('MEMCACHED_PASSWORD')],
            'options' => [
            ],
            'servers' => [
                [
                    'host' => env('MEMCACHED_HOST'),
                    'port' => env('MEMCACHED_PORT'),
                    'weight' => 100,
                ],
            ],
        ],
        'redis' => [
            'driver' => 'redis',
            'connection' => env('REDIS_CACHE_CONNECTION'),
            'lock_connection' => env('REDIS_CACHE_LOCK_CONNECTION'),
        ],
        'dynamodb' => [
            'driver' => 'dynamodb',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'table' => env('DYNAMODB_CACHE_TABLE'),
            'endpoint' => env('DYNAMODB_ENDPOINT'),
        ],
        'octane' => [
            'driver' => 'octane',
        ],
        'failover' => [
            'driver' => 'failover',
            'stores' => ['database', 'array'],
        ],
    ],
    'prefix' => env('CACHE_PREFIX'),
    'serializable_classes' => false,
];
