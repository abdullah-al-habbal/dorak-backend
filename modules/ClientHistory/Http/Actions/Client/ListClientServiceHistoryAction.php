<?php

declare(strict_types=1);

namespace Modules\ClientHistory\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Modules\ClientHistory\Handlers\ListClientServiceHistoryHandler;
use Modules\ClientHistory\Http\Requests\Client\ListClientServiceHistoryRequest;
use Modules\ClientHistory\Http\Resources\ClientServiceHistoryResource;
use Modules\Core\Http\Actions\BaseApiAction;

final class ListClientServiceHistoryAction extends BaseApiAction
{
    public function __construct(
        private readonly ListClientServiceHistoryHandler $handler,
    ) {}

    public function __invoke(ListClientServiceHistoryRequest $request): JsonResponse
    {
        $histories = $this->handler->handle($request->toQuery());

        return $this->paginated(
            paginator: $histories,
            resourceClass: ClientServiceHistoryResource::class,
        );
    }
}
