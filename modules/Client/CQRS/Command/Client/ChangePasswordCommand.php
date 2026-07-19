<?php

declare(strict_types=1);

namespace Modules\Client\CQRS\Command\Client;

final readonly class ChangePasswordCommand
{
    public function __construct(
        public string $clientId,
        public string $password,
    ) {}
}
