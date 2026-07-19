<?php

declare(strict_types=1);

namespace Modules\ClientRecommendation\ValuesObjects;

final readonly class ClientPreferenceVectorDataValueObject
{
    private function __construct(
        private array $vector,
        private array $metadata,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            vector: (array) ($data['vector'] ?? []),
            metadata: (array) ($data['metadata'] ?? []),
        );
    }

    public function toArray(): array
    {
        return [
            'vector' => $this->vector,
            'metadata' => $this->metadata,
        ];
    }

    public function vector(): array
    {
        return $this->vector;
    }

    public function metadata(): array
    {
        return $this->metadata;
    }
}
