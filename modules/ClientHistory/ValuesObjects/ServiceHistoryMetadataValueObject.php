<?php

declare(strict_types=1);

namespace Modules\ClientHistory\ValuesObjects;

use Webmozart\Assert\Assert;

final readonly class ServiceHistoryMetadataValueObject
{
    private function __construct(
        private array $productsUsed,
        private array $lengthSettings,
        private array $colorCodes,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            productsUsed: isset($data['products_used']) ? (array) $data['products_used'] : [],
            lengthSettings: isset($data['length_settings']) ? (array) $data['length_settings'] : [],
            colorCodes: isset($data['color_codes']) ? (array) $data['color_codes'] : [],
        );
    }

    public function toArray(): array
    {
        return [
            'products_used' => $this->productsUsed,
            'length_settings' => $this->lengthSettings,
            'color_codes' => $this->colorCodes,
        ];
    }

    public function productsUsed(): array
    {
        return $this->productsUsed;
    }

    public function lengthSettings(): array
    {
        return $this->lengthSettings;
    }

    public function colorCodes(): array
    {
        return $this->colorCodes;
    }
}
