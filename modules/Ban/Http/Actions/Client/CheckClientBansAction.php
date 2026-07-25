<?php

declare(strict_types=1);

namespace Modules\Ban\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Modules\Ban\Handlers\Client\CheckClientBanHandler;
use Modules\Ban\Http\Requests\Client\CheckClientBanRequest;
use Modules\Core\Http\Actions\BaseApiAction;

final class CheckClientBansAction extends BaseApiAction
{
    public function __construct(
        private readonly CheckClientBanHandler $handler,
    ) {}

    public function __invoke(CheckClientBanRequest $request, string $client): JsonResponse
    {
        $bans = $this->handler->handle($request->toQuery($client));

        $authenticatedClient = $request->user();

        if ($authenticatedClient->id !== $client) {
            return $this->forbidden();
        }

        return $this->ok([
            'banned' => $bans->isNotEmpty(),
            'bans' => $bans,
        ]);
    }
}
