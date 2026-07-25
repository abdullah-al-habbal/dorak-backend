<?php

declare(strict_types=1);

namespace Modules\Branch\CQRS\Command\Branch;

final readonly class LoginCommand
{
    public function __construct(
        public string $email,
        public string $password,
    ) {}
}
