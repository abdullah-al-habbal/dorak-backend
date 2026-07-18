<?php

declare(strict_types=1);

namespace Modules\Client\ValuesObjects;

final readonly class SocialLoginResult
{
    private function __construct(
        public bool $success,
        public ?string $failureReason,
        public ?string $token,
        public ?array $client,
        public ?bool $isNew,
    ) {}

    public static function success(string $token, array $clientData, bool $isNew): self
    {
        return new self(
            success: true,
            failureReason: null,
            token: $token,
            client: $clientData,
            isNew: $isNew,
        );
    }

    public static function invalidToken(): self
    {
        return new self(
            success: false,
            failureReason: 'invalid_token',
            token: null,
            client: null,
            isNew: null,
        );
    }

    public function isInvalidToken(): bool
    {
        return $this->failureReason === 'invalid_token';
    }
}
