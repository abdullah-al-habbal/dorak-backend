<?php

declare(strict_types=1);

namespace Modules\Chair\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Modules\Chair\Handlers\Client\UpdateChairHandler;
use Modules\Chair\Http\Requests\Client\UpdateChairRequest;
use Modules\Chair\Http\Resources\Shared\ChairResource;
use Modules\Core\Http\Actions\BaseApiAction;

final class UpdateChairAction extends BaseApiAction
{
    public function __construct(
        private readonly UpdateChairHandler $handler,
    ) {}

    public function __invoke(UpdateChairRequest $request, string $chair): JsonResponse
    {
        $chair = $this->handler->handle($request->toCommand($chair));

        return $this->ok(
            data: new ChairResource($chair),
            message: 'Chair updated successfully',
        );
    }
}
