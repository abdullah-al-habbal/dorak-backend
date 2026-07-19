<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\CQRS\Command;

use Modules\Client\Enums\UniverseEnum;

final readonly class UpdateDiscoveryPreferenceCommand
{
    public function __construct(
        public string $clientId,
        public ?UniverseEnum $preferredUniverse = null,
        public ?float $defaultRadius = null,
        public ?array $hiddenBrandIds = null,
        public ?bool $showUnavailable = null,
    ) {}
}
