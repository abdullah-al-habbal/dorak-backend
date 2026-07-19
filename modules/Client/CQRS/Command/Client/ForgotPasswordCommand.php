<?php

declare(strict_types=1);

namespace Modules\Client\CQRS\Command\Client;

final readonly class ForgotPasswordCommand
{
    public function __construct(
        public string $email,
    ) {}
}
