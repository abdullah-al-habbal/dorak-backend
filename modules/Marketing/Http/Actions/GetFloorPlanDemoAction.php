<?php
declare(strict_types=1);

namespace Modules\Marketing\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\Core\Helpers\ApiResponseTrait;
use Modules\Core\Services\TranslatorHandlerService;
use Modules\Marketing\Handlers\GetFloorPlanDemoHandler;

final class GetFloorPlanDemoAction
{
    use ApiResponseTrait;
    public function __construct(
        private readonly GetFloorPlanDemoHandler $handler,
        private readonly TranslatorHandlerService $translator,
    ) {}

    public function __invoke(): JsonResponse
    {
        $result = $this->handler->handle();

        if ($result === null) {
            return $this->notFound(
                message: $this->translator->translate('marketing::marketing.page_not_found'),
            );
        }

        return $this->success(data: $result);
    }
}
