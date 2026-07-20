<?php

declare(strict_types=1);

namespace Modules\Currency\ValuesObjects;

final readonly class ConvertCurrencyResult
{
    public function __construct(
        public string $from,
        public string $to,
        public float $amount,
        public float $result,
        public float $rate,
    ) {}
}
