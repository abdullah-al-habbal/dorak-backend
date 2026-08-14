<?php

// modules/ClientRecommendation/Features/ExploreRankingFeature.php

declare(strict_types=1);

namespace Modules\ClientRecommendation\Features;

use Laravel\Pennant\Attributes\Name;
use Modules\ClientRecommendation\Constants\RecommendationConstants;

#[Name('explore-ranking')]
final class ExploreRankingFeature
{
    public function resolve(?string $clientId): string
    {
        if ($clientId === null) {
            return RecommendationConstants::EXPLORE_RANKING_VARIANT_DEFAULT;
        }

        $bucket = (int) hexdec(substr(md5($clientId), 0, 8));

        return $bucket % 2 === 0 ? RecommendationConstants::EXPLORE_RANKING_VARIANT_FACTOR_WEIGHTS_V2 : RecommendationConstants::EXPLORE_RANKING_VARIANT_DEFAULT;
    }
}
