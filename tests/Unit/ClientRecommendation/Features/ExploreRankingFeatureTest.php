<?php

declare(strict_types=1);

use Modules\ClientRecommendation\Constants\RecommendationConstants;
use Modules\ClientRecommendation\Features\ExploreRankingFeature;

it('resolves default variant for null scope', function () {
    expect((new ExploreRankingFeature)->resolve(null))->toBe(RecommendationConstants::EXPLORE_RANKING_VARIANT_DEFAULT);
});

it('assigns factor-weights-v2 to a known client bucket', function () {
    expect((new ExploreRankingFeature)->resolve('11111111-1111-1111-1111-111111111111'))
        ->toBe(RecommendationConstants::EXPLORE_RANKING_VARIANT_FACTOR_WEIGHTS_V2);
});

it('assigns default variant to a known client bucket', function () {
    expect((new ExploreRankingFeature)->resolve('a5d1c9a1-1234-4abc-9def-000000000001'))
        ->toBe(RecommendationConstants::EXPLORE_RANKING_VARIANT_DEFAULT);
});

it('assignment is stable across calls', function () {
    $feature = new ExploreRankingFeature;

    expect($feature->resolve('abc-123'))->toBe($feature->resolve('abc-123'));
});

it('only produces known variants', function () {
    $feature = new ExploreRankingFeature;

    $variants = collect(range(1, 50))
        ->map(fn (int $i) => $feature->resolve("client-{$i}"))
        ->unique();

    expect($variants->diff([
        RecommendationConstants::EXPLORE_RANKING_VARIANT_DEFAULT,
        RecommendationConstants::EXPLORE_RANKING_VARIANT_FACTOR_WEIGHTS_V2,
    ]))->toBeEmpty();
});
