<?php

declare(strict_types=1);

namespace Modules\ClientFaceProfile\ValuesObjects;

final readonly class RecommendedCatalogItemIdsValueObject
{
    /** @param array<int> $ids */
    private function __construct(
        private array $ids,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            ids: isset($data['ids']) ? array_map('intval', (array) $data['ids']) : [],
        );
    }

    public function toArray(): array
    {
        return [
            'ids' => $this->ids,
        ];
    }

    public function ids(): array
    {
        return $this->ids;
    }
}
