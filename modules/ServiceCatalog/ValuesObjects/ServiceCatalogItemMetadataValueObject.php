<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\ValuesObjects;

use Webmozart\Assert\Assert;

final readonly class ServiceCatalogItemMetadataValueObject
{
    private function __construct(
        private ?int $durationMinutes,
        private ?int $minAgeYears,
        private ?int $maxAgeYears,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['duration_minutes']) ? (int) $data['duration_minutes'] : null,
            isset($data['min_age_years']) ? (int) $data['min_age_years'] : null,
            isset($data['max_age_years']) ? (int) $data['max_age_years'] : null,
        );
    }

    public function toArray(): array
    {
        return \array_filter([
            'duration_minutes' => $this->durationMinutes,
            'min_age_years' => $this->minAgeYears,
            'max_age_years' => $this->maxAgeYears,
        ], fn ($v) => $v !== null);
    }

    public function durationMinutes(): ?int
    {
        return $this->durationMinutes;
    }

    public function minAgeYears(): ?int
    {
        return $this->minAgeYears;
    }

    public function maxAgeYears(): ?int
    {
        return $this->maxAgeYears;
    }
}
