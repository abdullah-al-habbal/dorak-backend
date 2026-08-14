<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Modules\ClientInteraction\Handlers\CreateSavedFilterHandler;
use Modules\ClientInteraction\Http\Requests\Client\CreateSavedFilterRequest;
use Modules\ClientInteraction\Http\Resources\ClientSavedFilterResource;
use Modules\Core\Http\Actions\BaseApiAction;

final class CreateSavedFilterAction extends BaseApiAction
{
    public function __construct(
        private readonly CreateSavedFilterHandler $handler,
    ) {}

    public function __invoke(CreateSavedFilterRequest $request): JsonResponse
    {
        $command = $request->toCommand();
        $filter = $this->handler->handle($command);

        return $this->created(
            data: ClientSavedFilterResource::make($filter)->resolve($request),
        );
    }
}
