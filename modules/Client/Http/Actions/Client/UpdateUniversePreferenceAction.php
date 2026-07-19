<?php

declare(strict_types=1);

namespace Modules\Client\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Modules\Client\Handlers\Client\UpdateUniversePreferenceHandler;
use Modules\Client\Http\Requests\Client\UpdateUniversePreferenceRequest;
use Modules\Core\Http\Actions\BaseApiAction;

final class UpdateUniversePreferenceAction extends BaseApiAction
{
    public function __construct(
        private readonly UpdateUniversePreferenceHandler $handler,
    ) {}

    public function __invoke(UpdateUniversePreferenceRequest $request): JsonResponse
    {
        $command = $request->toCommand();
        $result = $this->handler->handle($command);

        return $this->updated(data: ['preferred_universe' => $result->preferredUniverse]);
    }
}
