<?php

declare(strict_types=1);

namespace Modules\Ban\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Modules\Ban\Handlers\Client\CheckClientBanHandler;
use Modules\Ban\Http\Requests\Client\CheckClientBanRequest;
use Modules\Core\Http\Actions\BaseApiAction;

final class CheckClientBanAction extends BaseApiAction
{
    public function __construct(
        private readonly CheckClientBanHandler $handler,
    ) {}

    public function __invoke(CheckClientBanRequest $request, string $client): JsonResponse
    {
        $isBanned = $this->handler->handle($request->toQuery($client));

        return $this->ok([
            'is_banned' => $isBanned,
        ]);
    }
}
