<?php

declare(strict_types=1);

namespace Modules\ClientFaceProfile\Enums;

enum DetectedFaceShapeEnum: string
{
    case Oval = 'oval';
    case Round = 'round';
    case Square = 'square';
    case Heart = 'heart';
    case Diamond = 'diamond';
    case Oblong = 'oblong';
    case Triangle = 'triangle';
}
