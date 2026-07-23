<?php

declare(strict_types=1);

namespace Modules\Barber\Enums;

enum BarberStatusEnum: string
{
    case Pending = 'pending';
    case Enabled = 'enabled';
    case Disabled = 'disabled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Enabled => 'Enabled',
            self::Disabled => 'Disabled',
        };
    }

    public function isEnabled(): bool
    {
        return $this === self::Enabled;
    }
}
