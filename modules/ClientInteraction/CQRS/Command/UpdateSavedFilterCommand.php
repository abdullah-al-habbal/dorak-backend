<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\CQRS\Command;

use Modules\ClientInteraction\ValuesObjects\FilterConfigurationValueObject;

final readonly class UpdateSavedFilterCommand
{
    public function __construct(
        public string $filterId,
        public string $clientId,
        public string $name,
        public FilterConfigurationValueObject $filterConfig,
    ) {}
}
