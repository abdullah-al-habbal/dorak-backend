<?php

declare(strict_types=1);

namespace Modules\Currency\CQRS\Query\Shared;

final readonly class ConvertCurrencyQuery
{
    public function __construct(
        public string $from,
        public string $to,
        public float $amount,
    ) {}
}
