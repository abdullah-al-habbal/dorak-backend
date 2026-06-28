<?php
declare(strict_types=1);

namespace Modules\Core\Http\Actions\HealthCheck;

use Illuminate\Http\JsonResponse;
use Modules\Core\Handlers\HealthCheck\HealthCheckHandler;
use Modules\Core\Http\Actions\BaseApiAction;

final class HealthCheckAction extends BaseApiAction
{
    public function __construct(
        private readonly HealthCheckHandler $handler,
    ) {}

    public function __invoke(): JsonResponse
    {
        $result = $this->handler->handle();

        // 503 removes the instance from load balancer rotation.
        // Degraded (cache/queue fail) stays 200 — app still serves traffic.
        $statusCode = $result['status'] === 'down' ? 503 : 200;

        return $this->success(
            data: $result,
            status: $statusCode,
        );
    }
}
