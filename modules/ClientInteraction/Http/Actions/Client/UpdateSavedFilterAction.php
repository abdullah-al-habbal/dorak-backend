<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Modules\ClientInteraction\Handlers\UpdateSavedFilterHandler;
use Modules\ClientInteraction\Http\Requests\Client\UpdateSavedFilterRequest;
use Modules\ClientInteraction\Http\Resources\ClientSavedFilterResource;
use Modules\Core\Http\Actions\BaseApiAction;

final class UpdateSavedFilterAction extends BaseApiAction
{
    public function __construct(
        private readonly UpdateSavedFilterHandler $handler,
    ) {}

    public function __invoke(UpdateSavedFilterRequest $request, string $filter): JsonResponse
    {
        $command = $request->toCommand($filter);
        $savedFilter = $this->handler->handle($command);

        return $this->ok(
            data: ClientSavedFilterResource::make($savedFilter)->resolve($request),
        );
    }
}
