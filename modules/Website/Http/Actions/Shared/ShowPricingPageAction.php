<?php

declare(strict_types=1);

namespace Modules\Website\Http\Actions\Shared;

use Illuminate\Contracts\View\View;
use Modules\Marketing\Handlers\Shared\GetMarketingPageHandler;

final class ShowPricingPageAction
{
    public function __construct(
        private readonly GetMarketingPageHandler $handler,
    ) {}

    public function __invoke(): View
    {
        $locale = app()->getLocale();
        $page = $this->handler->handle('pricing', $locale, 'all');

        return view('website::pages.pricing', [
            'page' => $page,
            'locale' => $locale,
        ]);
    }
}
