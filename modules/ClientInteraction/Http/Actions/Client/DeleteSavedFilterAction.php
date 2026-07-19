<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Modules\ClientInteraction\Handlers\DeleteSavedFilterHandler;
use Modules\Core\Http\Actions\BaseApiAction;

final class DeleteSavedFilterAction extends BaseApiAction
{
    public function __construct(
        private readonly DeleteSavedFilterHandler $handler,
    ) {}

    public function __invoke(string $filter): JsonResponse
    {
        $clientId = (string) request()->user()->id;

        $this->handler->handle($filter, $clientId);

        return $this->ok(data: []);
    }
}
