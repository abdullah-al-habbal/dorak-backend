<?php

declare(strict_types=1);

namespace Modules\Client\CQRS\Command\Client;

final readonly class SocialLoginCommand
{
    public function __construct(
        public string $provider,
        public string $accessToken,
    ) {}
}
