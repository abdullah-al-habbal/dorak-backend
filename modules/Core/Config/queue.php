<?php

declare(strict_types=1);

return [
    'default' => env('QUEUE_CONNECTION'),
    'connections' => [
        'sync' => [
            'driver' => 'sync',
        ],
        'database' => [
            'driver' => 'database',
            'connection' => env('DB_QUEUE_CONNECTION'),
            'table' => env('DB_QUEUE_TABLE'),
            'queue' => env('DB_QUEUE'),
            'retry_after' => (int) env('DB_QUEUE_RETRY_AFTER'),
            'after_commit' => false,
        ],
        'beanstalkd' => [
            'driver' => 'beanstalkd',
            'host' => env('BEANSTALKD_QUEUE_HOST'),
            'queue' => env('BEANSTALKD_QUEUE'),
            'retry_after' => (int) env('BEANSTALKD_QUEUE_RETRY_AFTER'),
            'block_for' => 0,
            'after_commit' => false,
        ],
        'sqs' => [
            'driver' => 'sqs',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'prefix' => env('SQS_PREFIX'),
            'queue' => env('SQS_QUEUE'),
            'suffix' => env('SQS_SUFFIX'),
            'region' => env('AWS_DEFAULT_REGION'),
            'after_commit' => false,
        ],
        'redis' => [
            'driver' => 'redis',
            'connection' => env('REDIS_QUEUE_CONNECTION'),
            'queue' => env('REDIS_QUEUE'),
            'retry_after' => (int) env('REDIS_QUEUE_RETRY_AFTER'),
            'block_for' => null,
            'after_commit' => false,
        ],
        'deferred' => [
            'driver' => 'deferred',
        ],
        'background' => [
            'driver' => 'background',
        ],
        'failover' => [
            'driver' => 'failover',
            'connections' => ['database', 'deferred'],
        ],
    ],
    'batching' => [
        'database' => env('DB_CONNECTION'),
        'table' => 'job_batches',
    ],
    'failed' => [
        'driver' => env('QUEUE_FAILED_DRIVER'),
        'database' => env('DB_CONNECTION'),
        'table' => 'failed_jobs',
    ],
];
