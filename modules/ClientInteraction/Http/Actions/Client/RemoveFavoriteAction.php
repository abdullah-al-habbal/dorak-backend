<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Modules\ClientInteraction\Handlers\RemoveFavoriteHandler;
use Modules\Core\Http\Actions\BaseApiAction;

final class RemoveFavoriteAction extends BaseApiAction
{
    public function __construct(
        private readonly RemoveFavoriteHandler $handler,
    ) {}

    public function __invoke(string $favorite): JsonResponse
    {
        $clientId = (string) request()->user()->id;

        $this->handler->handle($favorite, $clientId);

        return $this->ok(data: []);
    }
}
