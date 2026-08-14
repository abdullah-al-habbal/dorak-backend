<?php

declare(strict_types=1);

use Laravel\Pennant\Feature;
use Modules\Barber\Models\BarberModel;
use Modules\Client\Models\ClientModel;
use Modules\ClientRecommendation\Constants\RecommendationConstants;
use Modules\ClientRecommendation\Enums\EdgeTypeEnum;
use Modules\ClientRecommendation\Models\RecommendationEdgeModel;

it('uses default weights ranking margin for explore barbers', function () {
    Feature::define('explore-ranking', fn () => RecommendationConstants::EXPLORE_RANKING_VARIANT_DEFAULT);

    $client = ClientModel::factory()->create();

    $fav = BarberModel::factory()->freelancer()->create(['latitude' => 33.5, 'longitude' => 36.3]);
    $plain = BarberModel::factory()->freelancer()->create(['latitude' => 33.5, 'longitude' => 36.3]);

    RecommendationEdgeModel::create([
        'source_type' => 'client',
        'source_id' => $client->id,
        'target_type' => 'barber',
        'target_id' => $fav->id,
        'edge_type' => EdgeTypeEnum::Favorite,
        'weight' => 1.0,
    ]);

    $response = $this->actingAs($client, 'client')
        ->getJson('/api/v1/explore/barbers?latitude=33.5&longitude=36.3&radius=1000');

    $scores = collect($response->json('data'))->mapWithKeys(
        fn (array $item) => [$item['id'] => (float) $item['compatibility_score']]
    )->all();

    expect($scores[$fav->id])->toBeGreaterThan($scores[$plain->id]);
    expect($scores[$fav->id] - $scores[$plain->id])->toBeGreaterThan(0.29);
    expect($scores[$fav->id] - $scores[$plain->id])->toBeLessThan(0.31);
});

it('applies factor-weights-v2 margin when feature is active for the client', function () {
    Feature::define('explore-ranking', fn () => RecommendationConstants::EXPLORE_RANKING_VARIANT_FACTOR_WEIGHTS_V2);

    $client = ClientModel::factory()->create();

    $fav = BarberModel::factory()->freelancer()->create(['latitude' => 33.5, 'longitude' => 36.3]);
    $plain = BarberModel::factory()->freelancer()->create(['latitude' => 33.5, 'longitude' => 36.3]);

    RecommendationEdgeModel::create([
        'source_type' => 'client',
        'source_id' => $client->id,
        'target_type' => 'barber',
        'target_id' => $fav->id,
        'edge_type' => EdgeTypeEnum::Favorite,
        'weight' => 1.0,
    ]);

    $response = $this->actingAs($client, 'client')
        ->getJson('/api/v1/explore/barbers?latitude=33.5&longitude=36.3&radius=1000');

    $scores = collect($response->json('data'))->mapWithKeys(
        fn (array $item) => [$item['id'] => (float) $item['compatibility_score']]
    )->all();

    expect($scores[$fav->id])->toBeGreaterThan($scores[$plain->id]);
    expect($scores[$fav->id] - $scores[$plain->id])->toBeGreaterThan(0.31);
    expect($scores[$fav->id] - $scores[$plain->id])->toBeLessThan(0.36);
});
