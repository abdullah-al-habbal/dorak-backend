<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Enums;

enum InteractableTypeEnum: string
{
    case Brand = 'brand';
    case Branch = 'branch';
    case Barber = 'barber';
    case CatalogItem = 'catalog_item';
    case Service = 'service';
}
