<?php

declare(strict_types=1);

namespace Modules\ClientFaceProfile\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\ClientFaceProfile\CQRS\Query\GetFaceBasedRecommendationsQuery;
use Modules\ClientFaceProfile\Handlers\GetFaceBasedRecommendationsHandler;
use Modules\ClientFaceProfile\Http\Resources\ClientFaceAnalysisResultResource;
use Modules\Core\Http\Actions\BaseApiAction;

final class GetFaceBasedRecommendationsAction extends BaseApiAction
{
    public function __construct(
        private readonly GetFaceBasedRecommendationsHandler $handler,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $query = new GetFaceBasedRecommendationsQuery(
            clientId: $request->user()->id,
        );

        $results = $this->handler->handle($query);

        return $this->ok(
            data: ClientFaceAnalysisResultResource::collection($results),
        );
    }
}
