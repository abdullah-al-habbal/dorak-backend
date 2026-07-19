<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\CQRS\Command;

use Modules\ClientInteraction\Enums\FavorableTypeEnum;

final readonly class AddFavoriteCommand
{
    public function __construct(
        public string $clientId,
        public string $favorableId,
        public FavorableTypeEnum $favorableType,
    ) {}
}
