<?php

declare(strict_types=1);

namespace Modules\Client\CQRS\Command\Client;

final readonly class RegisterCommand
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public ?string $phone,
    ) {}
}
