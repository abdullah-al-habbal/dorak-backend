<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\ValuesObjects;

use Modules\BarberAffiliation\Models\BarberAffiliationModel;

final readonly class CreateAffiliationResult
{
    public function __construct(
        public BarberAffiliationModel $affiliation,
    ) {}
}
