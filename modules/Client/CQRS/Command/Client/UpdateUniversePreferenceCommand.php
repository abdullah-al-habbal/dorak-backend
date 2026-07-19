<?php

declare(strict_types=1);

namespace Modules\Client\CQRS\Command\Client;

use Modules\Client\Enums\UniverseEnum;

final readonly class UpdateUniversePreferenceCommand
{
    public function __construct(
        public string $clientId,
        public UniverseEnum $universe,
    ) {}
}
