<?php

declare(strict_types=1);

namespace Modules\ClientHistory\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\ClientHistory\CQRS\Query\ListClientServiceHistoryQuery;
use Modules\ClientHistory\Handlers\ListClientServiceHistoryHandler;
use Modules\ClientHistory\Http\Resources\ClientServiceHistoryResource;
use Modules\Core\Http\Actions\BaseApiAction;

final class ListClientServiceHistoryAction extends BaseApiAction
{
    public function __construct(
        private readonly ListClientServiceHistoryHandler $handler,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $query = new ListClientServiceHistoryQuery(
            clientId: $request->user()->id,
            perPage: (int) $request->input('per_page', '15'),
            catalogItemId: $request->input('catalog_item_id'),
        );

        $histories = $this->handler->handle($query);

        return $this->paginated(
            paginator: $histories,
            resourceClass: ClientServiceHistoryResource::class,
        );
    }
}
