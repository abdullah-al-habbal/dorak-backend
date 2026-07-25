<?php

declare(strict_types=1);

namespace Modules\Branch\ValuesObjects;

final readonly class BranchLoginResult
{
    private function __construct(
        public bool $success,
        public ?string $failureReason,
        public ?string $token,
        public ?array $branch,
    ) {}

    public static function success(string $token, array $branchData): self
    {
        return new self(
            success: true,
            failureReason: null,
            token: $token,
            branch: $branchData,
        );
    }

    public static function invalidCredentials(): self
    {
        return new self(
            success: false,
            failureReason: 'invalid_credentials',
            token: null,
            branch: null,
        );
    }

    public function isInvalidCredentials(): bool
    {
        return $this->failureReason === 'invalid_credentials';
    }
}
