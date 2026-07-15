<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\Enums;

enum AffiliableType: string
{
    case Branch = 'branch';
    case Brand = 'brand';

    public function label(): string
    {
        return $this->value;
    }
}
