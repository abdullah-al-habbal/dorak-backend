<?php

declare(strict_types=1);

namespace Modules\ClientHistory\Enums;

enum HistoryMediaType: string
{
    case Before = 'before';
    case After = 'after';
    case Reference = 'reference';
}
