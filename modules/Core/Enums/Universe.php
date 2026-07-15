<?php

declare(strict_types=1);

namespace Modules\Core\Enums;

enum Universe: string
{
    case All = 'all';
    case Men = 'men';
    case Women = 'women';

    public function label(): string
    {
        return $this->value;
    }
}
