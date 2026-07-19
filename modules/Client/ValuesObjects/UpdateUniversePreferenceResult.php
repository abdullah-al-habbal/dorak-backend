<?php

declare(strict_types=1);

namespace Modules\Client\ValuesObjects;

use Modules\Client\Enums\UniverseEnum;

final readonly class UpdateUniversePreferenceResult
{
    private function __construct(
        public bool $success,
        public ?string $failureReason,
        public UniverseEnum $preferredUniverse,
    ) {}

    public static function success(UniverseEnum $preferredUniverse): self
    {
        return new self(
            success: true,
            failureReason: null,
            preferredUniverse: $preferredUniverse,
        );
    }
}
