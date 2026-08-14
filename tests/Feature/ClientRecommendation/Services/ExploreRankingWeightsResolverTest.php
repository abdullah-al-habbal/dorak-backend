<?php

declare(strict_types=1);

use Laravel\Pennant\Feature;
use Modules\ClientRecommendation\Constants\RecommendationConstants;
use Modules\ClientRecommendation\Services\ExploreRankingWeightsResolver;
use Modules\ClientRecommendation\ValuesObjects\RecommendationFactorWeightsValueObject;

it('returns default weights for guest without a client scope', function () {
    $weights = app(ExploreRankingWeightsResolver::class)->resolveFor(null);

    expect($weights->toArray())->toBe(RecommendationFactorWeightsValueObject::defaults()->toArray());
});

it('returns factor-weights-v2 when feature activated for the client', function () {
    $clientId = '11111111-1111-1111-1111-111111111111';

    Feature::for($clientId)->activate('explore-ranking', RecommendationConstants::EXPLORE_RANKING_VARIANT_FACTOR_WEIGHTS_V2);

    $weights = app(ExploreRankingWeightsResolver::class)->resolveFor($clientId);

    expect($weights->alpha())->toBe(0.4);
    expect($weights->beta())->toBe(0.35);
    expect($weights->gamma())->toBe(0.15);
    expect($weights->geographic())->toBeGreaterThan(0.09)->toBeLessThan(0.11);
    expect(array_sum($weights->toArray()))->toBeGreaterThan(0.99)->toBeLessThan(1.01);
});

it('returns default weights when feature resolves to the default variant', function () {
    Feature::define('explore-ranking', fn () => RecommendationConstants::EXPLORE_RANKING_VARIANT_DEFAULT);

    $weights = app(ExploreRankingWeightsResolver::class)->resolveFor('a5d1c9a1-1234-4abc-9def-000000000001');

    expect($weights->toArray())->toBe(RecommendationFactorWeightsValueObject::defaults()->toArray());
});
