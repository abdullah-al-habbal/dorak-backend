<?php

declare(strict_types=1);

namespace Modules\ClientFaceProfile\Eloquent\Resolvers;

use Illuminate\Database\Eloquent\Collection;
use Modules\ClientFaceProfile\CQRS\Query\GetFaceBasedRecommendationsQuery;
use Modules\ClientFaceProfile\Models\ClientFaceAnalysisResultModel;

final class GetFaceBasedRecommendationsEloquentResolver
{
    public function resolve(GetFaceBasedRecommendationsQuery $query): Collection
    {
        return ClientFaceAnalysisResultModel::with('faceProfile')
            ->where('client_id', $query->clientId)
            ->latest('computed_at')
            ->get();
    }
}
