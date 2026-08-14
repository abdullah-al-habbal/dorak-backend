<?php

// modules/ClientRecommendation/ValuesObjects/RecommendationFactorWeightsValueObject.php

declare(strict_types=1);

namespace Modules\ClientRecommendation\ValuesObjects;

use Modules\ClientRecommendation\Constants\RecommendationConstants;

final readonly class RecommendationFactorWeightsValueObject
{
    private function __construct(
        private float $alpha,
        private float $beta,
        private float $gamma,
        private float $geographic,
    ) {}

    public static function defaults(): self
    {
        return self::fromArray(RecommendationConstants::DEFAULT_WEIGHTS);
    }

    public static function fromArray(array $data): self
    {
        $defaults = RecommendationConstants::DEFAULT_WEIGHTS;

        $total = (float) ($data['alpha'] ?? $defaults['alpha'])
            + (float) ($data['beta'] ?? $defaults['beta'])
            + (float) ($data['gamma'] ?? $defaults['gamma']);

        return new self(
            alpha: (float) ($data['alpha'] ?? $defaults['alpha']),
            beta: (float) ($data['beta'] ?? $defaults['beta']),
            gamma: (float) ($data['gamma'] ?? $defaults['gamma']),
            geographic: $total >= 1.0 ? 0.0 : (1.0 - $total),
        );
    }

    public function toArray(): array
    {
        return [
            'alpha' => $this->alpha,
            'beta' => $this->beta,
            'gamma' => $this->gamma,
            'geographic' => $this->geographic,
        ];
    }

    public function alpha(): float
    {
        return $this->alpha;
    }

    public function beta(): float
    {
        return $this->beta;
    }

    public function gamma(): float
    {
        return $this->gamma;
    }

    public function geographic(): float
    {
        return $this->geographic;
    }
}
