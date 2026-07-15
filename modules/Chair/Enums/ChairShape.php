<?php

declare(strict_types=1);

namespace Modules\Chair\Enums;

enum ChairShape: string
{
    case Rectangle = 'rectangle';
    case Circle = 'circle';
    case Ellipse = 'ellipse';

    public function label(): string
    {
        return $this->value;
    }
}
