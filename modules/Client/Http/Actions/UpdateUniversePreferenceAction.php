<?php

declare(strict_types=1);

namespace Modules\Client\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\Client\Handlers\UpdateUniversePreferenceHandler;
use Modules\Client\Http\Requests\UpdateUniversePreferenceRequest;
use Modules\Core\Http\Actions\BaseApiAction;

final class UpdateUniversePreferenceAction extends BaseApiAction
{
    public function __construct(
        private readonly UpdateUniversePreferenceHandler $handler,
    ) {}

    public function __invoke(UpdateUniversePreferenceRequest $request): JsonResponse
    {
        $result = $this->handler->handle(
            client: $request->user(),
            universe: $request->validated('universe'),
        );

        return $this->updated(data: ['preferred_universe' => $result->preferredUniverse]);
    }
}
