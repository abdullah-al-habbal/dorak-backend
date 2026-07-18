<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Enums;

enum HairTextureEnum: string
{
    case Straight = 'straight';
    case Wavy = 'wavy';
    case Curly = 'curly';
    case Coily = 'coily';

    public function label(): string
    {
        return $this->value;
    }
}
