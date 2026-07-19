<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Modules\ClientInteraction\Http\Requests\Client\AddFavoriteRequest;
use Modules\ClientInteraction\Http\Resources\ClientFavoriteResource;
use Modules\ClientInteraction\Handlers\AddFavoriteHandler;
use Modules\Core\Http\Actions\BaseApiAction;

final class AddFavoriteAction extends BaseApiAction
{
    public function __construct(
        private readonly AddFavoriteHandler $handler,
    ) {}

    public function __invoke(AddFavoriteRequest $request): JsonResponse
    {
        $command = $request->toCommand();
        $favorite = $this->handler->handle($command);

        return $this->created(
            data: ClientFavoriteResource::make($favorite)->resolve($request),
        );
    }
}
