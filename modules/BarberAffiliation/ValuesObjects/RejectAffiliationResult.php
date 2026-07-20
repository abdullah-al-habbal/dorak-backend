<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\ValuesObjects;

use Modules\BarberAffiliation\Models\BarberAffiliationModel;

final readonly class RejectAffiliationResult
{
    private function __construct(
        public bool $success,
        public ?BarberAffiliationModel $affiliation,
        public ?string $errorCode,
    ) {}

    public static function success(BarberAffiliationModel $affiliation): self
    {
        return new self(true, $affiliation, null);
    }

    public static function invalidStatus(): self
    {
        return new self(false, null, 'invalid_status');
    }
}
