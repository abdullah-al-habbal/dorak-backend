<?php

declare(strict_types=1);

namespace Modules\Client\ValuesObjects;

final readonly class ResetPasswordResult
{
    private function __construct(
        public bool $success,
        public ?string $failureReason,
    ) {}

    public static function success(): self
    {
        return new self(
            success: true,
            failureReason: null,
        );
    }

    public static function invalidCode(): self
    {
        return new self(
            success: false,
            failureReason: 'invalid_code',
        );
    }

    public function isInvalidCode(): bool
    {
        return $this->failureReason === 'invalid_code';
    }
}
