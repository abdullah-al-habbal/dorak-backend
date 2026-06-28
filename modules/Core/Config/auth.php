<?php
// /home/lenovo/work/me/dorak/dorak-backend/modules/Core/Config/auth.php
declare(strict_types=1);
namespace Modules\Core\Config;

use Modules\Client\Models\ClientModel;

return [
    'defaults' => [
        'guard' => env('AUTH_GUARD'),
        'passwords' => env('AUTH_PASSWORD_BROKER'),
    ],
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'clients',
        ],
    ],
    'providers' => [
        'clients' => [
            'driver' => 'eloquent',
            'model' => ClientModel::class,
        ],
    ],
    'passwords' => [
        'clients' => [
            'provider' => 'clients',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],
    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT'),
];
