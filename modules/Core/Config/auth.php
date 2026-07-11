<?php

// /home/lenovo/work/me/dorak/dorak-backend/modules/Core/Config/auth.php
declare(strict_types=1);

namespace Modules\Core\Config;

use Modules\Admin\Models\AdminModel;
use Modules\Barber\Models\BarberModel;
use Modules\Branch\Models\BranchModel;
use Modules\Client\Models\ClientModel;

return [
    'defaults' => [
        'guard' => env('AUTH_GUARD'),
        'passwords' => env('AUTH_PASSWORD_BROKER'),
    ],
    'guards' => [
        'client' => [
            'driver' => 'sanctum',
            'provider' => 'clients',
        ],
        'barber' => [
            'driver' => 'sanctum',
            'provider' => 'barbers',
        ],
        'barber_dashboard' => [
            'driver' => 'session',
            'provider' => 'barbers',
        ],
        'branch' => [
            'driver' => 'session',
            'provider' => 'branches',
        ],
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],
    ],
    'providers' => [
        'clients' => [
            'driver' => 'eloquent',
            'model' => ClientModel::class,
        ],
        'barbers' => [
            'driver' => 'eloquent',
            'model' => BarberModel::class,
        ],
        'branches' => [
            'driver' => 'eloquent',
            'model' => BranchModel::class,
        ],
        'admins' => [
            'driver' => 'eloquent',
            'model' => AdminModel::class,
        ],
    ],
    'passwords' => [
        'clients' => [
            'provider' => 'clients',
            'table' => 'password_reset_tokens_clients',
            'expire' => 60,
            'throttle' => 60,
        ],
        'barbers' => [
            'provider' => 'barbers',
            'table' => 'password_reset_tokens_barbers',
            'expire' => 60,
            'throttle' => 60,
        ],
        'branches' => [
            'provider' => 'branches',
            'table' => 'password_reset_tokens_branches',
            'expire' => 60,
            'throttle' => 60,
        ],
        'admins' => [
            'provider' => 'admins',
            'table' => 'password_reset_tokens_admins',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],
    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT'),
];
