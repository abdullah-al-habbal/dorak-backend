<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\ValuesObjects;

final readonly class InteractionContextValueObject
{
    public function __construct(
        public ?string $source = null,
        public ?string $query = null,
        public ?array $filters = null,
        public ?float $durationMs = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            source: $data['source'] ?? null,
            query: $data['query'] ?? null,
            filters: $data['filters'] ?? null,
            durationMs: isset($data['duration_ms']) ? (float) $data['duration_ms'] : null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'source' => $this->source,
            'query' => $this->query,
            'filters' => $this->filters,
            'duration_ms' => $this->durationMs,
        ], fn ($v) => $v !== null);
    }
}
