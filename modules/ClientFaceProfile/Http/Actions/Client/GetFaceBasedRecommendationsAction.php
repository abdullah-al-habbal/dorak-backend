<?php

declare(strict_types=1);

namespace Modules\ClientFaceProfile\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Modules\ClientFaceProfile\Handlers\GetFaceBasedRecommendationsHandler;
use Modules\ClientFaceProfile\Http\Requests\Client\GetFaceBasedRecommendationsRequest;
use Modules\ClientFaceProfile\Http\Resources\ClientFaceAnalysisResultResource;
use Modules\Core\Http\Actions\BaseApiAction;

final class GetFaceBasedRecommendationsAction extends BaseApiAction
{
    public function __construct(
        private readonly GetFaceBasedRecommendationsHandler $handler,
    ) {}

    public function __invoke(GetFaceBasedRecommendationsRequest $request): JsonResponse
    {
        $results = $this->handler->handle($request->toQuery());

        return $this->ok(
            data: ClientFaceAnalysisResultResource::collection($results),
        );
    }
}
