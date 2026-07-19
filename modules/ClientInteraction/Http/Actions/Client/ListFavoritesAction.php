<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Modules\ClientInteraction\Http\Requests\Client\ListFavoritesRequest;
use Modules\ClientInteraction\Http\Resources\ClientFavoriteResource;
use Modules\ClientInteraction\Handlers\ListFavoritesHandler;
use Modules\Core\Http\Actions\BaseApiAction;

final class ListFavoritesAction extends BaseApiAction
{
    public function __construct(
        private readonly ListFavoritesHandler $handler,
    ) {}

    public function __invoke(ListFavoritesRequest $request): JsonResponse
    {
        $query = $request->toQuery();
        $favorites = $this->handler->handle($query);

        return $this->paginated(paginator: $favorites, resourceClass: ClientFavoriteResource::class);
    }
}
