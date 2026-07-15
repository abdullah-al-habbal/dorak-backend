<?php

declare(strict_types=1);

namespace Modules\Core\Enums;

enum Locale: string
{
    case Ar = 'ar';
    case En = 'en';

    public function label(): string
    {
        return $this->value;
    }
}
