<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\CQRS\Command;

use Modules\ClientInteraction\ValuesObjects\FilterConfigurationValueObject;

final readonly class CreateSavedFilterCommand
{
    public function __construct(
        public string $clientId,
        public string $name,
        public FilterConfigurationValueObject $filterConfig,
    ) {}
}
