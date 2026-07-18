<?php

declare(strict_types=1);

namespace Modules\Chair\CQRS\Command\Client;

final readonly class UpdateChairCommand
{
    public function __construct(
        public string $chairId,
        public ?string $label,
        public ?string $status,
        public ?string $barberId,
        public ?array $uiMetadata,
    ) {}
}
