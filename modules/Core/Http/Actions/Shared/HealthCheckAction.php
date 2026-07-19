<?php

declare(strict_types=1);

namespace Modules\Core\Http\Actions\Shared;

use Illuminate\Http\JsonResponse;
use Modules\Core\Handlers\Shared\HealthCheckHandler;
use Modules\Core\Http\Actions\BaseApiAction;

final class HealthCheckAction extends BaseApiAction
{
    public function __construct(
        private readonly HealthCheckHandler $handler,
    ) {}

    public function __invoke(): JsonResponse
    {
        $result = $this->handler->handle();

        $statusCode = $result['status'] === 'down' ? 503 : 200;

        return $this->success(
            data: $result,
            status: $statusCode,
        );
    }
}
