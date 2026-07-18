<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Enums;

enum StylePeriodEnum: string
{
    case Classic = 'classic';
    case Modern = 'modern';

    public function label(): string
    {
        return $this->value;
    }
}
