<?php

declare(strict_types=1);

use Modules\ClientRecommendation\Features\ExploreRankingFeature;

it('resolves default variant for null scope', function () {
    expect((new ExploreRankingFeature)->resolve(null))->toBe(ExploreRankingFeature::VariantDefault);
});

it('assigns factor-weights-v2 to a known client bucket', function () {
    expect((new ExploreRankingFeature)->resolve('11111111-1111-1111-1111-111111111111'))
        ->toBe(ExploreRankingFeature::VariantFactorWeightsV2);
});

it('assigns default variant to a known client bucket', function () {
    expect((new ExploreRankingFeature)->resolve('a5d1c9a1-1234-4abc-9def-000000000001'))
        ->toBe(ExploreRankingFeature::VariantDefault);
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
        ExploreRankingFeature::VariantDefault,
        ExploreRankingFeature::VariantFactorWeightsV2,
    ]))->toBeEmpty();
});
