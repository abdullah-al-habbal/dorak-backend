<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\ValuesObjects;

use Webmozart\Assert\Assert;

final readonly class PriceRangeValueObject
{
    private function __construct(
        private float $min,
        private float $max,
        private string $currency,
    ) {}

    public static function fromArray(array $data): self
    {
        Assert::keyExists($data, 'min');
        Assert::keyExists($data, 'max');
        Assert::keyExists($data, 'currency');
        Assert::numeric($data['min']);
        Assert::numeric($data['max']);
        Assert::stringNotEmpty($data['currency']);
        Assert::lessThanEq((float) $data['min'], (float) $data['max']);

        return new self((float) $data['min'], (float) $data['max'], $data['currency']);
    }

    public function toArray(): array
    {
        return [
            'min' => $this->min,
            'max' => $this->max,
            'currency' => $this->currency,
        ];
    }

    public function min(): float
    {
        return $this->min;
    }

    public function max(): float
    {
        return $this->max;
    }

    public function currency(): string
    {
        return $this->currency;
    }
}
