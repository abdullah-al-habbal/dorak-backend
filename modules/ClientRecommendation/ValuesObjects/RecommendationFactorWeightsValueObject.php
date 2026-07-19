<?php

declare(strict_types=1);

namespace Modules\ClientRecommendation\ValuesObjects;

final readonly class RecommendationFactorWeightsValueObject
{
    private const float DEFAULT_ALPHA = 0.4;
    private const float DEFAULT_BETA = 0.3;
    private const float DEFAULT_GAMMA = 0.1;
    private const float DEFAULT_GEOGRAPHIC = 0.2;

    private function __construct(
        private float $alpha,
        private float $beta,
        private float $gamma,
        private float $geographic,
    ) {}

    public static function defaults(): self
    {
        return new self(
            alpha: self::DEFAULT_ALPHA,
            beta: self::DEFAULT_BETA,
            gamma: self::DEFAULT_GAMMA,
            geographic: self::DEFAULT_GEOGRAPHIC,
        );
    }

    public static function fromArray(array $data): self
    {
        $total = (float) ($data['alpha'] ?? self::DEFAULT_ALPHA)
            + (float) ($data['beta'] ?? self::DEFAULT_BETA)
            + (float) ($data['gamma'] ?? self::DEFAULT_GAMMA);

        return new self(
            alpha: (float) ($data['alpha'] ?? self::DEFAULT_ALPHA),
            beta: (float) ($data['beta'] ?? self::DEFAULT_BETA),
            gamma: (float) ($data['gamma'] ?? self::DEFAULT_GAMMA),
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

    public function alpha(): float { return $this->alpha; }
    public function beta(): float { return $this->beta; }
    public function gamma(): float { return $this->gamma; }
    public function geographic(): float { return $this->geographic; }
}
