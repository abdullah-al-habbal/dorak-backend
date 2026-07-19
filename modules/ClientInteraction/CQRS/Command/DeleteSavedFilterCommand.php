<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\CQRS\Command;

final readonly class DeleteSavedFilterCommand
{
    public function __construct(
        public string $filterId,
        public string $clientId,
    ) {}
}
