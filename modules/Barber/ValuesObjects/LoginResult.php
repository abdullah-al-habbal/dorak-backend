<?php

declare(strict_types=1);

namespace Modules\Barber\ValuesObjects;

final readonly class LoginResult
{
    private function __construct(
        public bool $success,
        public ?string $failureReason,
        public ?string $token,
        public ?array $barber,
    ) {}

    public static function success(string $token, array $barberData): self
    {
        return new self(
            success: true,
            failureReason: null,
            token: $token,
            barber: $barberData,
        );
    }

    public static function invalidCredentials(): self
    {
        return new self(
            success: false,
            failureReason: 'invalid_credentials',
            token: null,
            barber: null,
        );
    }

    public function isInvalidCredentials(): bool
    {
        return $this->failureReason === 'invalid_credentials';
    }
}
