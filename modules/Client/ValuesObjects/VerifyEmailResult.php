<?php

declare(strict_types=1);

namespace Modules\Client\ValuesObjects;

final readonly class VerifyEmailResult
{
    private function __construct(
        public bool $success,
        public ?string $failureReason,
        public ?bool $alreadyVerified,
    ) {}

    public static function success(): self
    {
        return new self(
            success: true,
            failureReason: null,
            alreadyVerified: false,
        );
    }

    public static function alreadyVerified(): self
    {
        return new self(
            success: false,
            failureReason: null,
            alreadyVerified: true,
        );
    }

    public static function invalidCode(): self
    {
        return new self(
            success: false,
            failureReason: 'invalid_code',
            alreadyVerified: false,
        );
    }

    public function isAlreadyVerified(): bool
    {
        return $this->alreadyVerified === true;
    }

    public function isInvalidCode(): bool
    {
        return $this->failureReason === 'invalid_code';
    }
}
