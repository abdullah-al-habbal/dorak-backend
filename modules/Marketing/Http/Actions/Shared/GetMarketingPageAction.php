<?php

declare(strict_types=1);

namespace Modules\Marketing\Http\Actions\Shared;

use Illuminate\Http\JsonResponse;
use Modules\Core\Helpers\ApiResponseTrait;
use Modules\Core\Services\TranslatorHandlerService;
use Modules\Marketing\Handlers\Shared\GetMarketingPageHandler;
use Modules\Marketing\Http\Requests\Shared\GetMarketingPageRequest;

final class GetMarketingPageAction
{
    use ApiResponseTrait;

    public function __construct(
        private readonly GetMarketingPageHandler $handler,
        private readonly TranslatorHandlerService $translator,
    ) {}

    public function __invoke(GetMarketingPageRequest $request, string $slug): JsonResponse
    {
        $locale = $request->getLocale();
        $universe = $request->getUniverse();

        $result = $this->handler->handle($slug, $locale, $universe);

        if ($result === null) {
            return $this->notFound(
                message: $this->translator->translate('marketing::marketing.page_not_found', locale: $locale),
            );
        }

        return $this->success(data: $result);
    }
}
