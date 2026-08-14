<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Modules\ClientInteraction\CQRS\Query\ListSavedFiltersQuery;
use Modules\ClientInteraction\Handlers\ListSavedFiltersHandler;
use Modules\ClientInteraction\Http\Resources\ClientSavedFilterResource;
use Modules\Core\Http\Actions\BaseApiAction;

final class ListSavedFiltersAction extends BaseApiAction
{
    public function __construct(
        private readonly ListSavedFiltersHandler $handler,
    ) {}

    public function __invoke(): JsonResponse
    {
        $query = new ListSavedFiltersQuery(
            clientId: (string) request()->user()->id,
        );

        $filters = $this->handler->handle($query);

        return $this->ok(
            data: ClientSavedFilterResource::collection($filters)->resolve(request()),
        );
    }
}
