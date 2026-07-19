<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\CQRS\Command;

final readonly class RemoveFavoriteCommand
{
    public function __construct(
        public string $clientId,
        public string $favoriteId,
    ) {}
}
