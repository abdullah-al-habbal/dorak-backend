<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Modules\ClientInteraction\Http\Resources\ClientSavedFilterResource;
use Modules\ClientInteraction\CQRS\Query\GetSavedFilterQuery;
use Modules\ClientInteraction\Handlers\GetSavedFilterHandler;
use Modules\Core\Http\Actions\BaseApiAction;

final class ShowSavedFilterAction extends BaseApiAction
{
    public function __construct(
        private readonly GetSavedFilterHandler $handler,
    ) {}

    public function __invoke(string $filter): JsonResponse
    {
        $query = new GetSavedFilterQuery(
            filterId: $filter,
            clientId: (string) request()->user()->id,
        );

        $savedFilter = $this->handler->handle($query);

        return $this->ok(
            data: ClientSavedFilterResource::make($savedFilter)->resolve(request()),
        );
    }
}
