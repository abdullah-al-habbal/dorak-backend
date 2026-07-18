<?php

declare(strict_types=1);

namespace Modules\Client\ValuesObjects;

final readonly class UpdateUniversePreferenceResult
{
    private function __construct(
        public bool $success,
        public ?string $failureReason,
        public ?string $preferredUniverse,
    ) {}

    public static function success(string $preferredUniverse): self
    {
        return new self(
            success: true,
            failureReason: null,
            preferredUniverse: $preferredUniverse,
        );
    }
}
