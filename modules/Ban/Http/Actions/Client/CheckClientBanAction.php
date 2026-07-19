<?php

declare(strict_types=1);

namespace Modules\Ban\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Modules\Client\Models\ClientModel;
use Modules\Core\Http\Actions\BaseApiAction;

final class CheckClientBanAction extends BaseApiAction
{
    public function __invoke(string $client): JsonResponse
    {
        $client = ClientModel::findOrFail($client);

        return $this->ok([
            'is_banned' => $client->isBanned(),
        ]);
    }
}
