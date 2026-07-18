<?php

declare(strict_types=1);

namespace Modules\Client\ValuesObjects;

final readonly class RefreshTokenResult
{
    private function __construct(
        public bool $success,
        public ?string $token,
    ) {}

    public static function success(string $token): self
    {
        return new self(
            success: true,
            token: $token,
        );
    }
}
