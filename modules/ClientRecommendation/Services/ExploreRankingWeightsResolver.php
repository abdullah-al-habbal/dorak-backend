<?php

declare(strict_types=1);

namespace Modules\ClientRecommendation\Services;

use Laravel\Pennant\Feature;
use Modules\ClientRecommendation\Features\ExploreRankingFeature;
use Modules\ClientRecommendation\ValuesObjects\RecommendationFactorWeightsValueObject;

final class ExploreRankingWeightsResolver
{
    public function resolveFor(?string $clientId): RecommendationFactorWeightsValueObject
    {
        if ($clientId === null) {
            return RecommendationFactorWeightsValueObject::defaults();
        }

        $variant = Feature::for($clientId)->value(ExploreRankingFeature::class);

        return $variant === ExploreRankingFeature::VariantFactorWeightsV2
            ? $this->factorWeightsV2()
            : RecommendationFactorWeightsValueObject::defaults();
    }

    private function factorWeightsV2(): RecommendationFactorWeightsValueObject
    {
        return RecommendationFactorWeightsValueObject::fromArray([
            'alpha' => 0.40,
            'beta' => 0.35,
            'gamma' => 0.15,
            'geographic' => 0.10,
        ]);
    }
}
