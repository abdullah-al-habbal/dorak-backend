<?php

declare(strict_types=1);

namespace Modules\ClientRecommendation\Observers;

use Modules\Client\Models\ClientModel;
use Modules\ClientInteraction\Models\ClientFavoriteModel;
use Modules\ClientRecommendation\Enums\EdgeTypeEnum;
use Modules\ClientRecommendation\Models\RecommendationEdgeModel;

final class ClientFavoriteObserver
{
    public function created(ClientFavoriteModel $favorite): void
    {
        RecommendationEdgeModel::create([
            'source_type' => ClientModel::class,
            'source_id' => $favorite->client_id,
            'target_type' => $favorite->favorable_type,
            'target_id' => $favorite->favorable_id,
            'edge_type' => EdgeTypeEnum::Favorite->value,
            'weight' => 1.0,
        ]);
    }

    public function deleted(ClientFavoriteModel $favorite): void
    {
        RecommendationEdgeModel::where('source_type', ClientModel::class)
            ->where('source_id', $favorite->client_id)
            ->where('target_type', $favorite->favorable_type)
            ->where('target_id', $favorite->favorable_id)
            ->where('edge_type', EdgeTypeEnum::Favorite->value)
            ->delete();
    }
}
