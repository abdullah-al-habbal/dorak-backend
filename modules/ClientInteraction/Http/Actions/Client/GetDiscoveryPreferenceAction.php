<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Modules\ClientInteraction\Http\Resources\ClientDiscoveryPreferenceResource;
use Modules\ClientInteraction\Handlers\GetDiscoveryPreferenceHandler;
use Modules\Core\Http\Actions\BaseApiAction;

final class GetDiscoveryPreferenceAction extends BaseApiAction
{
    public function __construct(
        private readonly GetDiscoveryPreferenceHandler $handler,
    ) {}

    public function __invoke(): JsonResponse
    {
        $clientId = (string) request()->user()->id;

        $preference = $this->handler->handle($clientId);

        return $this->ok(
            data: ClientDiscoveryPreferenceResource::make($preference)->resolve(request()),
        );
    }
}
