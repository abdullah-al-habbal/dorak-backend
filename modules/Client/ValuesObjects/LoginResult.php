<?php

declare(strict_types=1);

namespace Modules\Client\ValuesObjects;

final readonly class LoginResult
{
    private function __construct(
        public bool $success,
        public ?string $failureReason,
        public ?string $token,
        public ?array $client,
    ) {}

    public static function success(string $token, array $clientData): self
    {
        return new self(
            success: true,
            failureReason: null,
            token: $token,
            client: $clientData,
        );
    }

    public static function invalidCredentials(): self
    {
        return new self(
            success: false,
            failureReason: 'invalid_credentials',
            token: null,
            client: null,
        );
    }

    public function isInvalidCredentials(): bool
    {
        return $this->failureReason === 'invalid_credentials';
    }
}
