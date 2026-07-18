<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Enums;

enum MaintenanceLevelEnum: string
{
    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';

    public function label(): string
    {
        return $this->value;
    }
}
