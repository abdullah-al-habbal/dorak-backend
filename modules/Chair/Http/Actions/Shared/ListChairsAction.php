<?php

declare(strict_types=1);

namespace Modules\Chair\Http\Actions\Shared;

use Illuminate\Http\JsonResponse;
use Modules\Chair\Handlers\Shared\ListChairsHandler;
use Modules\Chair\Http\Requests\Shared\ListChairsRequest;
use Modules\Chair\Http\Resources\Shared\ChairResource;
use Modules\Core\Http\Actions\BaseApiAction;

final class ListChairsAction extends BaseApiAction
{
    public function __construct(
        private readonly ListChairsHandler $handler,
    ) {}

    public function __invoke(ListChairsRequest $request): JsonResponse
    {
        $query = $request->toQuery();
        $result = $this->handler->handle($query);

        return $this->paginated(
            paginator: $result,
            resourceClass: ChairResource::class,
        );
    }
}
