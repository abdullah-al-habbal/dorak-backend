<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Enums;

enum InteractionTypeEnum: string
{
    case View = 'view';
    case Search = 'search';
    case Favorite = 'favorite';
}
