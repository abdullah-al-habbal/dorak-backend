<?php

declare(strict_types=1);

namespace Modules\Client\ValuesObjects;

final readonly class LogoutResult
{
    private function __construct(
        public bool $success,
    ) {}

    public static function success(): self
    {
        return new self(success: true);
    }
}
