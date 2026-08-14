<?php

declare(strict_types=1);

namespace Modules\ClientRecommendation\Features;

use Laravel\Pennant\Attributes\Name;

#[Name('explore-ranking')]
final class ExploreRankingFeature
{
    public const string VariantDefault = 'default';

    public const string VariantFactorWeightsV2 = 'factor-weights-v2';

    public function resolve(?string $clientId): string
    {
        if ($clientId === null) {
            return self::VariantDefault;
        }

        $bucket = (int) hexdec(substr(md5($clientId), 0, 8));

        return $bucket % 2 === 0 ? self::VariantFactorWeightsV2 : self::VariantDefault;
    }
}
