<?php

declare(strict_types=1);

namespace Modules\Client\ValuesObjects;

final readonly class ForgotPasswordResult
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
}
