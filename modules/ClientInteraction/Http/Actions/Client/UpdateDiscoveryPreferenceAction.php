<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Modules\ClientInteraction\Http\Requests\Client\UpdateDiscoveryPreferenceRequest;
use Modules\ClientInteraction\Http\Resources\ClientDiscoveryPreferenceResource;
use Modules\ClientInteraction\Handlers\UpdateDiscoveryPreferenceHandler;
use Modules\Core\Http\Actions\BaseApiAction;

final class UpdateDiscoveryPreferenceAction extends BaseApiAction
{
    public function __construct(
        private readonly UpdateDiscoveryPreferenceHandler $handler,
    ) {}

    public function __invoke(UpdateDiscoveryPreferenceRequest $request): JsonResponse
    {
        $command = $request->toCommand();
        $preference = $this->handler->handle($command);

        return $this->ok(
            data: ClientDiscoveryPreferenceResource::make($preference)->resolve($request),
        );
    }
}
