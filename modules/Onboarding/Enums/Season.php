<?php

declare(strict_types=1);

namespace Modules\Onboarding\Enums;

enum Season: string
{
    case Spring = 'spring';
    case Summer = 'summer';
    case Autumn = 'autumn';
    case Winter = 'winter';

    public static function fromMonth(int $month): self
    {
        return match ($month) {
            3, 4, 5 => self::Spring,
            6, 7, 8 => self::Summer,
            9, 10, 11 => self::Autumn,
            default => self::Winter,
        };
    }
}
