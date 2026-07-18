<?php

declare(strict_types=1);

namespace Modules\Client\ValuesObjects;

final readonly class SendEmailVerificationResult
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

    public function isAlreadyVerified(): bool
    {
        return $this->alreadyVerified === true;
    }
}
