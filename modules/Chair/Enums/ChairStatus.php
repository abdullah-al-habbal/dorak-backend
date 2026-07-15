<?php

declare(strict_types=1);

namespace Modules\Chair\Enums;

enum ChairStatus: string
{
    case Available = 'available';
    case Occupied = 'occupied';
    case Maintenance = 'maintenance';

    public function label(): string
    {
        return $this->value;
    }

    public function isBookable(): bool
    {
        return $this === self::Available;
    }
}
